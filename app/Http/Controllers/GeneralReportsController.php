<?php

namespace App\Http\Controllers;

use App\Models\OfficeRequest;
use App\Models\Requisition;
use App\Models\RequisitionItemsSerial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()->name ?? 'default';

        if (in_array($role, ['laboratory', 'dean', 'superadmin'])) {
            return view("reports.{$role}-index");
        }
    }


    public function filter(Request $request)
    {
        $filterType = $request->input('filter');
        $data = [];

        switch ($filterType) {
            case 'lost_damaged':
                $data = $this->getLostDamagedItems();
                $data->put('damage_lost_items', true);
                break;
            case 'requisition':
                $data = $this->getRequisitionItems();
                break;
            default:
                return collect();
                break;
        }
        return response()->json($data);
    }

    protected function getLostDamagedItems()
    {
        $items = RequisitionItemsSerial::whereHas('serialRelatedItem', function ($query) {
            $query->where('condition', 'Damaged');
        })
            ->with(['equipmentBelongs.category', 'serialRelatedItem'])
            ->get();

        return collect(['items' => $items, 'damage_lost_items' => true]);
    }

    protected function getRequisitionItems()
    {
        return Requisition::with(['category', 'items.serials.equipmentBelongs', 'items.serials.serialRelatedItem', 'students', 'instructor'])->get();
    }


    public function site_reports()
    {
        $user = Auth::user();
        $role = $user->roles->first()->name;
        $match = str_replace(" ", "-", $role);
        if (in_array($role, ['site secretary', 'dean', 'superadmin'])) {
            return view("reports.site.{$match}-reports");
        }
    }


    public function filter_type(Request $request)
    {
        $filterType = $request->input('filterType');

        $data = [];


        switch ($filterType) {
            case 'equipment':
                $data = $this->getOfficeRequestEquipment();
                break;
            case 'supplies':
                $data = $this->getOfficeRequestSupplies();
                break;
            case 'equipment_requisition':
                $data = $this->getOfficeRequestRequisitionEquipment();
                break;
            case 'equipment_requisition_all':
                $data = $this->getOfficeRequestRequisitionEquipmentAll();
                break;
            default:
                return collect();
                break;
        }

        return response()->json($data);
    }

    protected function getOfficeRequestEquipment()
    {
        $results = OfficeRequest::select(
            'office_requests.*',
            'borrowed_equipment.office_requests_id',
            'borrowed_equipment.borrow_status',
            'equipment.item as equipment_item',
            'equipment_items.serial_no as equipment_serial_no',
            'equipment_items.note as equipment_notes',
            'borrowed_equipment.date_returned',
            'borrowed_equipment.item_id',
            'borrowed_equipment.equipment_serial_id',
            'equipment_items.equipment_id',
            'equipment_items.status as item_status',
            'equipment.*',
            'users.name as request_by',
            'office_requests.created_at as date_added'
        )
            ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->leftJoin('equipment', 'equipment_items.equipment_id', '=', 'equipment.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Equipments')
            ->where('equipment_items.status', 'Damaged')
            ->get();

        foreach ($results as $index => $item) {
            $item->serial_no_count = $index + 1;
        }

        return $results;
    }


    protected function getOfficeRequestSupplies()
    {
        return OfficeRequest::select(
            'office_requests.*',
            'supplies.item',
            'office_requests.created_at as date_added',
            'users.name as request_by',
        )
            ->leftJoin('supplies', 'office_requests.item_id', '=', 'supplies.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Supplies')
            ->get();
    }

    protected function getOfficeRequestRequisitionEquipment()
    {
        return OfficeRequest::selectRaw('
                office_requests.id,
                MAX(users.name) as request_by,
                MAX(equipment.item) as equipment_item,
                GROUP_CONCAT(equipment_items.serial_no) as serial_numbers,
                MAX(office_requests.quantity_requested) as quantity_requested,
                MAX(office_requests.purpose) as purpose,
                MAX(office_requests.created_at) as date_added
            ')
            ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->leftJoin('equipment', 'equipment_items.equipment_id', '=', 'equipment.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Equipments')
            ->groupBy('office_requests.id')
            ->get();
    }


    protected function getOfficeRequestRequisitionEquipmentAll()
    {
        $rawData = OfficeRequest::select(
            'office_requests.id',
            'users.name as request_by',
            'equipment.item as equipment_item',
            'equipment_items.serial_no',
            'equipment_items.note',
            'office_requests.quantity_requested',
            'office_requests.purpose',
            'office_requests.created_at as date_added',
            'borrowed_equipment.borrow_status'
        )
            ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->leftJoin('equipment', 'equipment_items.equipment_id', '=', 'equipment.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Equipments')
            ->get();

        $grouped = $rawData->groupBy('id')->map(function ($items) {
            return [
                'id' => $items->first()->id,
                'request_by' => $items->first()->request_by,
                'equipment_item' => $items->first()->equipment_item,
                'serial_numbers' => implode(', ', $items->pluck('serial_no')->filter()->all()),
                'notes' => implode(', ', $items->pluck('note')->filter()->all()),
                'quantity_requested' => $items->first()->quantity_requested,
                'purpose' => $items->first()->purpose,
                'borrow_status' => $items->first()->borrow_status,
                'date_added' => $items->first()->date_added,
            ];
        })->values();

        return $grouped;
    }
}
