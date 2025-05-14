<?php

namespace App\Http\Controllers\SuperAdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Construction;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use Illuminate\Http\Request;

class SConstructionController extends Controller
{
    public function index()
    {
        $constructions = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'General Construction');
            })
            ->get();

        return view('superadmin.construction.index', compact('constructions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('superadmin.construction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'items.*.serial_no' => 'required|string',
                'items.*.condition' => 'required|string',
                'equipment'  => 'required|string',
                'brand'  => 'required|string',
                'description'  => 'nullable|string',
                'date_acquired'  => 'required|string',
                'unit'  => 'required|string',
            ],
            [
                'items.*.serial_no.required' => 'Please enter Serial No'
            ]
        );
        $serialNumbers = $request->input('serial_no');
        $conditions = $request->input('condition');
        $equipment = $request->input('equipment');
        $brand = $request->input('brand');
        $dateAcquired = $request->input('date_acquired');
        $unit = $request->input('unit');

        $quantity = count($serialNumbers);

        $surveying = LaboratoryEquipment::create([
            'category_id' => 4,
            'equipment' => $equipment,
            'brand' => $brand,
            'description' => $request->input('description'),
            'quantity' => $quantity,
            'date_acquired' => $dateAcquired,
            'unit' => $unit,
        ]);

        foreach ($serialNumbers as $index => $serialNo) {
            LaboratoryEquipmentItem::create([
                'laboratory_equipment_id' => $surveying->id,
                'serial_no' => $serialNo,
                'condition' => $conditions[$index],
            ]);
        }

        return redirect()->to('/superadmin/construction')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = LaboratoryEquipment::with('items')->find($id);
        return view('superadmin.construction.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) // Note the variable name change here to match the model binding
    {
        $construction = LaboratoryEquipment::with('items')->find($id);
        return view('superadmin.construction.edit', compact('construction')); // Make sure 'surveying' is the variable used
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
        $testings = LaboratoryEquipment::findOrFail($id);
        $existingItemIds = $request->input('serial_id', []);
        $newItems = $request->input('items', []);
        $testings->update([
            'equipment' => $request->input('equipment'),
            'description' => $request->input('description'),
            'brand' => $request->input('brand'),
            'unit' => $request->input('unit'),
            'quantity' =>  count(array_filter($newItems, function ($item) {
                return !empty($item['serial_no']);
            })),
        ]);

        // Track existing item IDs for deletion
        $receivedItemIds = [];

        // Update existing items
        foreach ($existingItemIds as $itemId) {
            if (isset($newItems[$itemId])) {
                $item = $testings->items()->find($itemId);
                if ($item) {
                    $item->update($newItems[$itemId]);
                    $receivedItemIds[] = $itemId; // Track updated item IDs
                }
            }
        }

        // Handle new items (items with keys starting with 'new_')
        foreach ($newItems as $key => $itemData) {
            if (strpos($key, 'new_') === 0) {
                $newItem = $testings->items()->create($itemData);
                $receivedItemIds[] = $newItem->id; // Ensure new items are tracked
            }
        }

        // Optional: Delete items that were not included in the update
        $itemsToDelete = $testings->items()->whereNotIn('id', $receivedItemIds)->get();
        foreach ($itemsToDelete as $item) {
            $item->delete();
        }

        return redirect()->to('/superadmin/construction');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(Request $request, $id)
    {
        $construction = LaboratoryEquipment::find($id);
        $construction->delete();

        return back()->with([
            'message' => 'Successfully deleted!',
            'alert-type' => 'danger'
        ]);
    }
}
