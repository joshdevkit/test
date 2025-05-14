<?php

namespace App\Http\Controllers\OfficePanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Models\BorrowedEquipment;
use App\Models\Equipment;
use App\Models\EquipmentItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::with(['items' => function ($query) {
            $query->where('status', '!=', 'Queue');
        }])->get();

        return view('office.equipment.index', compact('equipments'));
    }



    public function equipment_items()
    {
        /**
         * @var App\Models\User
         */
        $user = Auth::user();

        $equipmentItems = EquipmentItems::with('equipment')->get();
        if ($user->hasRole('superadmin')) {
            return view('superadmin.office-equipment-items', compact('equipmentItems'));
        }
        return view('office.equipment.items', compact('equipmentItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('office.equipment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentRequest $request)
    {
        $serialNO = $request->input('serial_no');
        $serialNoCount = is_array($serialNO) ? count($serialNO) : 0;
        $equipment = Equipment::create($request->validated() + [
            'quantity' => $serialNoCount,
            'unit' => $request->unit,
            'item' => $request->item,
            'brand_description' => $request->brand_description,
            'location' => $request->location,
            'date_delivered' => $request->date_delivered,
        ]);

        foreach ($serialNO as $serial) {
            EquipmentItems::create([
                'equipment_id' => $equipment->id,
                'serial_no' => $serial,
            ]);
        }

        return redirect()->to('/office/equipment')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = Equipment::with('items')->find($id);
        return view('office.equipment.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $equipment = Equipment::with('items')->find($id);
        return view('office.equipment.edit', compact('equipment'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'serial_no' => 'required|array',
            'item' => 'required|string',
            'brand_description' => 'required|string',
            'unit' => 'required|string',
            'location' => 'required|string',
            'date_delivered' => 'required|date',
        ]);

        // Find the equipment record
        $equipment = Equipment::findOrFail($id);

        // Update the equipment details
        $equipment->update($request->only(['item', 'brand_description', 'unit', 'location', 'date_delivered']));

        // Fetch existing serial numbers for the equipment
        $existingItems = $equipment->items()->pluck('serial_no')->toArray();

        // Get the new serial numbers from the request
        $newSerials = $request->input('serial_no');

        // Determine which serials to delete
        $itemsToDelete = array_diff($existingItems, $newSerials);
        EquipmentItems::whereIn('serial_no', $itemsToDelete)->delete();

        // Update or create items
        foreach ($newSerials as $serial) {
            EquipmentItems::updateOrCreate(
                ['serial_no' => $serial, 'equipment_id' => $id],
                ['equipment_id' => $id]
            );
        }

        // Recalculate the quantity based on the total number of items
        $totalItems = $equipment->items()->count();
        $equipment->update(['quantity' => $totalItems]);

        return redirect()->to('/equipment')->with('message', 'Successfully updated!');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    /**
     * Delete all selected Service at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Equipment::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }


    public function equipment_items_history($id)
    {
        /**
         * @var App\Models\User
         */
        $user = Auth::user();
        $itemHistory = BorrowedEquipment::where('equipment_serial_id', $id)->where('borrow_status', 'Returned')->with(['items', 'requestFrom.requestBy'])->get();

        if ($user->hasRole('superadmin')) {
            return view('superadmin.office-equipment-items-history', compact('itemHistory'));
        }
        return view('office.equipment.equipment-history', compact('itemHistory'));
    }
}
