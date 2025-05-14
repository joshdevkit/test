<?php

namespace App\Http\Controllers\SuperAdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ComputerEngineering;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use Illuminate\Http\Request;

class SComputerController extends Controller
{
    public function index()
    {
        $computerEngineering = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Computer Engineering');
            })
            ->get();

        return view('superadmin.computer_engineering.index', compact('computerEngineering'));
    }

    public function create()
    {
        return view('superadmin.computer_engineering.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipment' => 'required',
            'brand' => 'required',
            'date_acquired' => 'required',
            'unit' => 'required',
        ]);

        $serialNumbers = $request->input('serial_no');
        $conditions = $request->input('condition');
        $equipment = $request->input('equipment');
        $brand = $request->input('brand');
        $dateAcquired = $request->input('date_acquired');
        $unit = $request->input('unit');

        $quantity = count($serialNumbers);

        $computerEngineering = LaboratoryEquipment::create([
            'category_id' => 1,
            'equipment' => $equipment,
            'brand' => $brand,
            'quantity' => $quantity,
            'date_acquired' => $dateAcquired,
            'unit' => $unit,
        ]);

        foreach ($serialNumbers as $index => $serialNo) {
            LaboratoryEquipmentItem::create([
                'laboratory_equipment_id' => $computerEngineering->id,
                'serial_no' => $serialNo,
                'condition' => $conditions[$index],
            ]);
        }

        return redirect()->to('/superadmin/computer_engineering')->with([
            'message' => 'Successfully created!',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = LaboratoryEquipment::with('items')->find($id);
        return view('superadmin.computer_engineering.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $computerEngineering = LaboratoryEquipment::with('items')->findOrFail($id);
        return view('superadmin.computer_engineering.edit', compact('computerEngineering'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'equipment' => 'required|string',
                'brand' => 'required|string',
                'date_acquired' => 'required|date',
                'unit' => 'required|string',
                'items.*.serial_no' => 'required|string',
                'items.*.condition' => 'required|string',
            ],
            [
                'items.*.serial_no.required' => 'Please enter Serial No'
            ]
        );

        // Find the ComputerEngineering model by its ID
        $computerEngineering = LaboratoryEquipment::findOrFail($id);

        // Get existing item IDs and new items from the request
        $existingItemIds = $request->input('serial_id', []);
        $newItems = $request->input('items', []);

        // Update the main attributes of ComputerEngineering
        $computerEngineering->update([
            'equipment' => $request->input('equipment'),
            'brand' => $request->input('brand'),
            'date_acquired' => $request->input('date_acquired'),
            'unit' => $request->input('unit'),
            // Update the quantity based on existing and new items
            'quantity' =>  count(array_filter($newItems, function ($item) {
                return !empty($item['serial_no']);
            })),
        ]);

        // Track existing item IDs for deletion
        $receivedItemIds = [];

        // Update existing items
        foreach ($existingItemIds as $itemId) {
            if (isset($newItems[$itemId])) {
                $item = $computerEngineering->items()->find($itemId);
                if ($item) {
                    $item->update($newItems[$itemId]);
                    $receivedItemIds[] = $itemId; // Track updated item IDs
                }
            }
        }

        // Handle new items (items with keys starting with 'new_')
        foreach ($newItems as $key => $itemData) {
            if (strpos($key, 'new_') === 0) {
                $newItem = $computerEngineering->items()->create($itemData);
                $receivedItemIds[] = $newItem->id; // Ensure new items are tracked
            }
        }

        // Optional: Delete items that were not included in the update
        $itemsToDelete = $computerEngineering->items()->whereNotIn('id', $receivedItemIds)->get();
        foreach ($itemsToDelete as $item) {
            $item->delete();
        }

        return redirect()->to('/superadmin/computer_engineering')->with('message', 'Successfully updated!');
    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(Request $request, $id)
    {
        $computerEngineering = LaboratoryEquipment::find($id);
        $computerEngineering->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
