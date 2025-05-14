<?php

namespace App\Http\Controllers\OfficePanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuppliesRequest;
use App\Models\Supplies;
use App\Models\SuppliesItems;
use App\Notifications\SupplyRunningLowNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class SuppliesController extends Controller
{
    public function index()
    {
        $supplies = Supplies::with('items')->get();

        return view('office.supplies.index', compact('supplies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('office.supplies.create');
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

        return redirect()->to('/office/supplies')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }


    public function show($id)
    {
        $data = Supplies::with('items')->find($id);
        return view('office.supplies.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supply = Supplies::with('items')->find($id);
        return view('office.supplies.edit', compact('supply'));
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

        return redirect()->to('/office/supplies')->with('message', 'Successfully updated!');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(Supplies $supply)
    {
        $supply->delete();

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
        Supplies::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }


    public function sendLowStockNotification(Request $request)
    {
        /**
         * @var App\Models\User;
         */
        $user = auth()->user();

        $notificationSentKey = 'low_stock_notification_sent_today_' . $user->id;
        if (Cache::has($notificationSentKey)) {
            return response()->json(['message' => 'Low stock notifications have already been sent today.'], 400);
        }

        Cache::put($notificationSentKey, true, now()->addDay());

        $supplies = Supplies::whereIn('id', $request->supply_ids)->get();

        foreach ($supplies as $supply) {
            $user->notify(new SupplyRunningLowNotification($supply));
        }

        return response()->json(['message' => 'Low stock notifications sent successfully.']);
    }
}
