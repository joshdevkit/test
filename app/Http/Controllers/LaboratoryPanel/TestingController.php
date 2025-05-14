<?php

namespace App\Http\Controllers\LaboratoryPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestingRequest;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use Illuminate\Http\Request;
use App\Models\Testing;
use App\Models\TestingSerials;

class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testings = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Testing & Mechanics');
            })
            ->get();

        // $testings = Testing::with('items')->get();

        return view('laboratory.testing.index', compact('testings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laboratory.testing.create');
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
                'description'  => 'required|string',
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
            'category_id' => 3,
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

        return redirect()->to('/testings')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function printAll(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $testings = Testing::paginate($perPage, ['*'], 'page', $page);

        return view('laboratory.testing.print', compact('testings'));
    }


    public function show($id)
    {
        $data = LaboratoryEquipment::with('items')->find($id);
        return view('laboratory.testing.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) // Note the variable name change here to match the model binding
    {
        $testing = LaboratoryEquipment::with('items')->find($id);
        return view('laboratory.testing.edit', compact('testing')); // Make sure 'surveying' is the variable used
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

        return redirect()->to('/testings');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(LaboratoryEquipment $testing)  // Here it should be Testing, not TestingRequest
    {
        $testing->delete();

        return back()->with([
            'message' => 'Successfully deleted!',
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
        Testing::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
