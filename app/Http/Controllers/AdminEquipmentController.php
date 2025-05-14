<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use App\Models\EquipmentItems;
use Illuminate\Http\Request;

class AdminEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.equipment.create');
    }

    /**
     * Store a newly created resource in storage.
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

        return redirect()->to('/superadmin/equipment')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Equipment::with('items')->find($id);
        return view('superadmin.equipment.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipment = Equipment::with('items')->find($id);
        return view('superadmin.equipment.edit', compact('equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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

        return redirect()->to('/superadmin/equipment')->with('message', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $equipment = Equipment::find($id);
        $equipment->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
