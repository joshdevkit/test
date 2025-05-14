<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuppliesRequest;
use App\Models\Supplies;
use App\Models\SuppliesItems;
use Illuminate\Http\Request;

class AdminSuppliesController extends Controller
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
        return view('superadmin.supplies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SuppliesRequest $request)
    {
        $serialNO = $request->input('serial_no');
        $serialNoCount = is_array($serialNO) ? count($serialNO) : 0;
        $supply = Supplies::create($request->validated() + [
            'quantity' => $serialNoCount,
            'unit' => $request->unit,
            'item' => $request->item,
            'brand_description' => $request->brand_description,
            'location' => $request->location,
            'date_delivered' => $request->date_delivered,
        ]);

        foreach ($serialNO as $serial) {
            SuppliesItems::create([
                'supplies_id' => $supply->id,
                'serial_no' => $serial,
            ]);
        }

        return redirect()->to('/superadmin/supplies')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = Supplies::with('items')->find($id);
        return view('superadmin.supplies.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supply = Supplies::with('items')->find($id);
        return view('superadmin.supplies.edit', compact('supply'));
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

        // Find the supply record
        $supply = Supplies::findOrFail($id);

        // Update the supply details
        $supply->update($request->only(['unit', 'item', 'brand_description', 'location', 'date_delivered']));

        // Fetch existing serial numbers for the supply
        $existingItems = $supply->items()->pluck('serial_no')->toArray();

        // Get the new serial numbers from the request
        $newSerials = $request->input('serial_no');

        // Determine which serials to delete
        $itemsToDelete = array_diff($existingItems, $newSerials);
        SuppliesItems::whereIn('serial_no', $itemsToDelete)->delete();

        // Update or create items
        foreach ($newSerials as $serial) {
            SuppliesItems::updateOrCreate(
                ['serial_no' => $serial, 'supplies_id' => $id],
                ['supplies_id' => $id]
            );
        }

        // Recalculate the quantity based on the total number of items
        $totalItems = $supply->items()->count();
        $supply->update(['quantity' => $totalItems]);

        return redirect()->to('/superadmin/supplies')->with('message', 'Successfully updated!');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        $supply = Supplies::find($id);
        $supply->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}
