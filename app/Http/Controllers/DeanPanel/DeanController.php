<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\BorrowedEquipment;
use App\Models\ComputerEngineering;
use App\Models\Construction;
use App\Models\Equipment;
use App\Models\EquipmentItems;
use App\Models\Fluid;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use App\Models\OfficeRequest;
use App\Models\Requisition;
use App\Models\RequisitionItemsSerial;
use App\Models\Supplies;
use App\Models\Surveying;
use App\Models\Testing;

use Illuminate\Http\Request;

class DeanController extends Controller
{
    public function index()
    {
        $totals = [
            'computer' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Computer Engineering');
                })
                ->count('equipment'),
            'construction' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'General Construction');
                })
                ->count('equipment'),
            'surveying' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Surveying');
                })
                ->count('equipment'),
            'testing' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Testing & Mechanics');
                })
                ->count('equipment'),
            'fluid' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Hydraulics and Fluids');
                })
                ->count('equipment'),
            'supplies' => Supplies::count(),
            'equipments' => Equipment::count(),
            'transactions' => Requisition::count(),
            'office_transac' => OfficeRequest::count(),
        ];
        return view('dean.dashboard', compact('totals'));
    }


    public function lab_items()
    {
        $equipments = LaboratoryEquipmentItem::with('equipment.category')->get();
        return view('dean.laboratory-items', compact('equipments'));
    }

    public function history($id)
    {
        //
        $history = RequisitionItemsSerial::with(['serialRelatedItem', 'requisition.requisitions.instructor'])->where('equipment_serial_id', $id)->get();
        // dd($history);
        return view('dean.equipment-items-history', compact('history'));
    }


    public function equipment_items()
    {
        $equipmentItems = EquipmentItems::with('equipment')->get();
        return view('dean.laboratory-equipment', compact('equipmentItems'));
    }

    public function equipment_items_history($id)
    {
        $itemHistory = BorrowedEquipment::where('equipment_serial_id', $id)->where('borrow_status', 'Returned')->with(['items', 'requestFrom.requestBy'])->get();
        return view('dean.equipment-history', compact('itemHistory'));
    }
}
