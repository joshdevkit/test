<?php

namespace App\Http\Controllers\LaboratoryPanel;

use App\Http\Controllers\Controller;

use App\Http\Requests\ConstructionRequest;
use App\Models\Construction;
use App\Models\ConstructionSerials;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use Illuminate\Http\Request;

class ConstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $constructions = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'General Construction');
            })
            ->get();

        // $constructions = Construction::with('items')->get();

        return view('laboratory.construction.index', compact('constructions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laboratory.construction.create');
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
        $serialNumbers = $request->input('serial_no');
        $conditions = $request->input('condition');
        $equipment = $request->input('equipment');
        $brand = $request->input('brand');
        $dateAcquired = $request->input('date_acquired');
        $unit = $request->input('unit');

        $quantity = count($serialNumbers);

        $computerEngineering = LaboratoryEquipment::create([
            'category_id' => 4,
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

        return redirect()->to('/constructions')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = LaboratoryEquipment::with('items')->find($id);
        return view('laboratory.construction.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) // Note the variable name change here to match the model binding
    {
        $construction = LaboratoryEquipment::with('items')->find($id);
        return view('laboratory.construction.edit', compact('construction'));
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
        $request->validate(
            [
                'items.*.serial_no' => 'required|string',
                'items.*.condition' => 'required|string',
            ],
            [
                'items.*.serial_no.required' => 'Please enter Serial No'
            ]
        );
        $construction = LaboratoryEquipment::findOrFail($id);

        // Get existing item IDs and new items from the request
        $existingItemIds = $request->input('serial_id', []);
        $newItems = $request->input('items', []);

        // Update the main attributes of ComputerEngineering
        $construction->update([
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
                $item = $construction->items()->find($itemId);
                if ($item) {
                    $item->update($newItems[$itemId]);
                    $receivedItemIds[] = $itemId; // Track updated item IDs
                }
            }
        }

        // Handle new items (items with keys starting with 'new_')
        foreach ($newItems as $key => $itemData) {
            if (strpos($key, 'new_') === 0) {
                $newItem = $construction->items()->create($itemData);
                $receivedItemIds[] = $newItem->id; // Ensure new items are tracked
            }
        }

        // Optional: Delete items that were not included in the update
        $itemsToDelete = $construction->items()->whereNotIn('id', $receivedItemIds)->get();
        foreach ($itemsToDelete as $item) {
            $item->delete();
        }

        return redirect()->to('/constructions');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(LaboratoryEquipment $construction)
    {
        $construction->delete();

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
        Construction::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
