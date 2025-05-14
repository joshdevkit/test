<?php

namespace App\Http\Controllers\LaboratoryPanel;

use App\Http\Controllers\Controller;

use App\Http\Requests\SurveyingRequest;
use App\Models\Category;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use App\Models\Surveying;
use App\Models\SurveyingSerials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveyings = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Surveying');
            })
            ->get();

        return view('laboratory.surveying.index', compact('surveyings'));
    }

    public function printAll(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;


        $surveying = Surveying::paginate($perPage, ['*'], 'page', $page);

        return view('laboratory.surveying.print', compact('surveying'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laboratory.surveying.create');
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
            'description' => 'required',
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

        $surveying = LaboratoryEquipment::create([
            'category_id' => 2,
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

        return redirect()->to('/surveyings')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = LaboratoryEquipment::with('items')->find($id);
        return view('laboratory.surveying.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $surveying = LaboratoryEquipment::with('items')->find($id);
        return view('laboratory.surveying.edit', compact('surveying'));
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
        // Find the ComputerEngineering model by its ID
        $surveyings = LaboratoryEquipment::findOrFail($id);
        $existingItemIds = $request->input('serial_id', []);
        $newItems = $request->input('items', []);

        $surveyings->update([
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
                $item = $surveyings->items()->find($itemId);
                if ($item) {
                    $item->update($newItems[$itemId]);
                    $receivedItemIds[] = $itemId; // Track updated item IDs
                }
            }
        }

        // Handle new items (items with keys starting with 'new_')
        foreach ($newItems as $key => $itemData) {
            if (strpos($key, 'new_') === 0) {
                $newItem = $surveyings->items()->create($itemData);
                $receivedItemIds[] = $newItem->id; // Ensure new items are tracked
            }
        }

        // Optional: Delete items that were not included in the update
        $itemsToDelete = $surveyings->items()->whereNotIn('id', $receivedItemIds)->get();
        foreach ($itemsToDelete as $item) {
            $item->delete();
        }

        return redirect()->to('/surveying')->with('message', 'Successfully updated!');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(LaboratoryEquipment $surveying)
    {
        $surveying->delete();

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
        Surveying::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
