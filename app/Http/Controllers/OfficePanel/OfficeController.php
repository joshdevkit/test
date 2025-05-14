<?php

namespace App\Http\Controllers\OfficePanel;

use App\Models\Supplies;
use App\Models\TransactionOffice;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficeController extends Controller
{
    public function index()
    {
        $totals = [
            'supplies' => Supplies::count('item'),
            'equipment' => Equipment::count('item')
        ];
        $mainRequests = DB::table('office_requests')
            ->select(
                'office_requests.*',
                'users.name as requested_by_name',
                'equipment1.quantity as supply_remaining_quantity'
            )
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->leftJoin('supplies', function ($join) {
                $join->on('office_requests.item_id', '=', 'supplies.id')
                    ->where('office_requests.item_type', '=', 'Supplies');
            })
            ->leftJoin('equipment as equipment1', function ($join) {
                $join->on('office_requests.item_id', '=', 'equipment1.id')
                    ->where('office_requests.item_type', '=', 'Equipments');
            })
            ->orderBy('office_requests.created_at', 'DESC')
            ->get();

        $serialNumbers = DB::table('office_requests')
            ->select(
                'office_requests.id',
                DB::raw("GROUP_CONCAT(DISTINCT CASE
        WHEN office_requests.item_type = 'Supplies' THEN supplies_items.serial_no
        WHEN office_requests.item_type = 'Equipments' THEN equipment_items.serial_no
     END SEPARATOR ', ') as serial_no")
            )
            ->leftJoin('supplies_items', function ($join) {
                $join->on('office_requests.item_id', '=', 'supplies_items.supplies_id')
                    ->where('office_requests.item_type', '=', 'Supplies');
            })
            ->leftJoin('equipment_items', function ($join) {
                $join->on('office_requests.item_id', '=', 'equipment_items.equipment_id')
                    ->where('office_requests.item_type', '=', 'Equipments');
            })
            ->groupBy('office_requests.id')
            ->get();

        $itemNames = DB::table('office_requests')
            ->select(
                'office_requests.id',
                DB::raw("GROUP_CONCAT(DISTINCT CASE
        WHEN office_requests.item_type = 'Supplies' THEN supplies.item
        WHEN office_requests.item_type = 'Equipments' THEN equipment2.item
     END SEPARATOR ', ') as item_name")
            )
            ->leftJoin('supplies', function ($join) {
                $join->on('office_requests.item_id', '=', 'supplies.id')
                    ->where('office_requests.item_type', '=', 'Supplies');
            })
            ->leftJoin('equipment as equipment2', function ($join) {
                $join->on('office_requests.item_id', '=', 'equipment2.id')
                    ->where('office_requests.item_type', '=', 'Equipments');
            })
            ->groupBy('office_requests.id')
            ->get();

        $requests = $mainRequests->map(function ($request) use ($serialNumbers, $itemNames) {
            $serialNumber = $serialNumbers->firstWhere('id', $request->id);
            $itemName = $itemNames->firstWhere('id', $request->id);

            return (object) array_merge((array) $request, [
                'serial_no' => $serialNumber->serial_no ?? null,
                'item_name' => $itemName->item_name ?? null,
            ]);
        });

        return view('office.dashboardo', compact('totals', 'requests'));
    }
}
