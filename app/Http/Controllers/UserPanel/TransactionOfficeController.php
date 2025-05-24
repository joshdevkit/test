<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use App\Models\BorrowedEquipment;
use Illuminate\Http\Request;
use App\Models\TransactionOffice;
use App\Models\Equipment;
use App\Models\EquipmentItems;
use App\Models\OfficeRequest;
use App\Models\Supplies;
use App\Models\SuppliesItems;
use App\Models\User;
use App\Notifications\ApproveOfficeMultipleRequest;
use App\Notifications\BorrowerNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TransactionStatusNotification;
use App\Notifications\UserNotifications;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class TransactionOfficeController extends Controller
{
    public function selectCategory(Request $request)
    {
        $category = $request->input('category');
        if ($category === 'equipments') {
            $items = Equipment::with('items')->get()->map(function ($equipment) {
                $nonQueuedNonDamagedItems = $equipment->items->filter(function ($item) {
                    return $item->status !== 'Queue' && $item->status !== 'Damaged';
                });
                return [
                    'id' => $equipment->id,
                    'item' => $equipment->item,
                    'count' => $nonQueuedNonDamagedItems->count(),
                ];
            })->toArray();
        } elseif ($category === 'supplies') {
            $items = Supplies::with('items')->get()->map(function ($equipment) {
                $availableItems = $equipment->items->filter(function ($item) {
                    return $item->disposed !== 'Out';
                });
                return [
                    'id' => $equipment->id,
                    'item' => $equipment->item,
                    'count' => $availableItems->count(),
                ];
            })->toArray();
        } else {
            $items = [];
        }

        return redirect()->back()->with([
            'category' => $category,
            'items' => $items,
        ]);
    }

    public function create()
    {
        $supplies = Supplies::with('items')->get();
        $user = Auth::user();
        $equipments = Equipment::with('items')->get();
        return view('profile.office_user.create', compact('supplies', 'equipments', 'user'));
    }





    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required',
            'purpose' => 'required|string',
            'category' => 'required|string',
        ]);

        $totalCount = 0;

        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'serial-') && is_array($value)) {
                $totalCount += count($value);
            }
        }

        if ($data['category'] === 'Supplies') {
            foreach ($request->input('items') as $item) {
                $item_id = $item['item_id'];
                $quantity_requested = $item['quantity'];

                // Fetch and shuffle the supplies items
                $suppliesItems = SuppliesItems::where('supplies_id', $item_id)->where('disposed', '!=', 'Out')->get()->shuffle();

                // Keep track of disposed items
                $disposedItemsCount = 0;

                foreach ($suppliesItems as $suppliesItem) {
                    if ($disposedItemsCount < $quantity_requested) {
                        $suppliesItem->disposed = 'Out'; // Mark the item as disposed
                        $suppliesItem->save();
                        $disposedItemsCount++;
                    } else {
                        break;
                    }
                }

                // Create the office request
                OfficeRequest::create([
                    'item_id' => $item_id,
                    'item_type' => $request->input('category'),
                    'quantity_requested' => $item['quantity'],
                    'requested_by' => Auth::id(),
                    'purpose' => $request->input('purpose'),
                ]);
            }
        }



        if ($data['category'] === 'Equipments') {

            $officeRequest = OfficeRequest::create([
                'item_type' => $request->input('category'),
                'quantity_requested' =>  $totalCount,
                'requested_by' => Auth::id(),
                'purpose' => $request->input('purpose'),
            ]);

            foreach ($request->input('items') as $index => $item) {
                $item_id = $item['item_id'];

                $serialsKey = 'serial-' . $index;
                $serials = $request->input($serialsKey);

                if ($serials && is_array($serials)) {
                    foreach ($serials as $serial) {

                        $itemsSerialData = EquipmentItems::where('id', $serial)->first();
                        $itemsSerialData->status = 'Queue';
                        $itemsSerialData->save();

                        BorrowedEquipment::create([
                            'office_requests_id' => $officeRequest->id,
                            'item_id' => $item_id,
                            'equipment_serial_id' => $serial,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('office_user.create')->with('success', 'Request submitted successfully!');
    }

    public function index()
    {
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


        // dd($requests);


        // dd($requests);


        /**
         * @var App\Models\User;
         */
        // dd($requests);
        $user = Auth::user();
        if ($user->hasRole('site secretary')) {
            return view('office.transactions.index', compact('requests'));
        } else if ($user->hasRole('superadmin')) {
            return view('superadmin.site-transactions', compact('requests'));
        } else {
            return view('dean.site-transactions', compact('requests'));
        }
    }

    public function details($id)
    {
        $request = DB::table('office_requests')
            ->where('id', $id)
            ->select('item_type')
            ->first();

        if ($request && $request->item_type === 'Equipments') {
            $requestDetails = DB::table('office_requests')
                ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
                ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
                ->leftJoin('equipment', 'borrowed_equipment.item_id', '=', 'equipment.id')
                ->where('office_requests.id', $id)
                ->select(
                    'office_requests.*',
                    'equipment.item as equipment_item',
                    'borrowed_equipment.item_id',
                    'borrowed_equipment.equipment_serial_id',
                    'equipment_items.serial_no as equipment_serial',
                    'equipment_items.status as equipment_status',
                    'equipment_items.note as equipment_notes',
                    'borrowed_equipment.borrow_status as borrowed_equipment_status'
                )
                ->get();

            return response()->json($requestDetails);
            // dd($requestDetails);
        }
        // return view('office.transactions.details', compact('requestDetails'));
    }

    public function view_details($id)
    {
        $request = DB::table('office_requests')
            ->where('id', $id)
            ->select('item_type')
            ->first();

        if ($request && $request->item_type === 'Equipments') {
            $requestDetails = DB::table('office_requests')
                ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
                ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
                ->leftJoin('equipment', 'borrowed_equipment.item_id', '=', 'equipment.id')
                ->where('office_requests.id', $id)
                ->select(
                    'office_requests.*',
                    'equipment.item as equipment_item',
                    'borrowed_equipment.item_id',
                    'borrowed_equipment.equipment_serial_id',
                    'equipment_items.serial_no as equipment_serial',
                    'equipment_items.note as equipment_notes'

                )
                ->get();
        }
        return view('office.transactions.details', compact('requestDetails'));
    }

    public function decisions(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:office_requests,id',
            'status' => 'required|string|in:Approved,Declined,Received,Returned,Not Returned,Damaged,Repaired,XXX',
        ]);

        $requestItem = OfficeRequest::findOrFail($request->id);
        if ($requestItem->item_type === "Equipments") {
            $borrowedEquipment = BorrowedEquipment::with('items')->where('office_requests_id', $request->id)->get();
            $initialItems = [];
            $intialItemname = "";
            $itemType = $requestItem->item_type;

            foreach ($borrowedEquipment as $item) {
                $initialItems[] = $item->equipment_serial_id;
                $intialItemname = $item->item;
            }
            if ($requestItem->status === "Pending") {
                $requestItem->status = $request->status;
                $requestItem->save();

                if (in_array($request->status, ['Approved', 'Declined', 'Received', 'Returned', 'Not Returned', 'Damaged', 'Repaired', 'XXX'])) {
                    $usersWithRole = User::role('user')->where('id', $requestItem->requested_by)->get();
                    $message = "The {$itemType}\n'{$intialItemname}' has been {$requestItem->status}.";
                    Notification::send($usersWithRole, new UserNotifications($message, $initialItems, $itemType));
                }
            }

            if ($requestItem->status === "Approved") {
                $requestItem->status = $request->status;
                $requestItem->save();
            }

            if ($requestItem->status === "Received") {
                $requestItem->status = $request->status;
                $requestItem->save();
            }


            return response()->json(['message' => 'Status updated successfully!'], 200);
        } else if ($requestItem->item_type === "Supplies") {
            $requestItem = OfficeRequest::findOrFail($request->id);
            $itemType = $requestItem->item_type;
            $itemId = $requestItem->item_id;
            $quantityRequested = $requestItem->quantity_requested;

            $item = Supplies::findOrFail($itemId);
            $itemName = $item->item;

            if ($request->status === 'Approved') {
                $requestItem->status = $request->status;
                $requestItem->save();
            } else if ($request->status === 'Received') {
                $requestItem->status = "Received";
                $item->quantity -= $quantityRequested;
                $item->save();
                $requestItem->save();
            } else if ($request->status === "Declined") {
                $suppliesItems = SuppliesItems::where('supplies_id', $itemId)
                    ->where('disposed', 'Out')
                    ->limit($quantityRequested)
                    ->get();

                foreach ($suppliesItems as $suppliesItem) {
                    $suppliesItem->disposed = 'Okay';
                    $suppliesItem->save();
                }

                $requestItem->status = "Declined";
                $requestItem->save();
            }


            if (in_array($request->status, ['Approved', 'Declined', 'Received', 'Returned', 'Not Returned', 'Damaged', 'Repaired', 'XXX'])) {
                $usersWithRole = User::role('user')->where('id', $requestItem->requested_by)->get();
                $message = "The {$itemType}\n'{$itemName}' has been {$requestItem->status}.";
                Notification::send($usersWithRole, new UserNotifications($message, $itemId, $itemType));
            }

            return response()->json(['message' => 'Status updated successfully!'], 200);
        }
    }





    private function removeNotificationByTransactionId($transactionId)
    {
        $notifications = session('notifications', []);

        foreach ($notifications as $index => $notification) {
            if (isset($notification['transaction_id']) && $notification['transaction_id'] == $transactionId) {
                unset($notifications[$index]);
                break;
            }
        }

        session(['notifications' => $notifications]);
    }

    public function disapprove($id)
    {
        $transaction = TransactionOffice::find($id);
        $transaction->status = 'disapproved';
        $transaction->save();

        // Remove the corresponding notification from the session
        $this->removeNotificationByTransactionId($id);

        return back()->with('success', 'Request disapproved successfully!');
    }

    public function returned($id)
    {
        $transaction = TransactionOffice::find($id);

        if ($transaction->status == 'returned') {
            return back()->with('error', 'This item has already been marked as returned.');
        }

        $transaction->status = 'returned';
        $transaction->datetime_returned = Carbon::now();
        $transaction->days_not_returned = Carbon::now()->diffInDays($transaction->datetime_borrowed);
        $transaction->save();

        // Fetch the item from the supplies or equipment table
        $supply = Supplies::where('item', $transaction->item)->first();
        $equipment = Equipment::where('item', $transaction->item)->first();

        if ($supply) {
            // Update the supply quantity
            $supply->quantity += $transaction->quantity;
            $supply->save();
        } elseif ($equipment) {
            // Update the equipment quantity
            $equipment->quantity += $transaction->quantity;
            $equipment->save();
        }

        return back()->with('success', 'Item marked as returned successfully!');
    }

    public function damaged($id)
    {
        $transaction = TransactionOffice::find($id);

        if ($transaction->status == 'damaged') {
            return back()->with('error', 'This item has already been marked as damaged.');
        }

        $transaction->status = 'damaged';
        $transaction->datetime_returned = Carbon::now();
        $transaction->days_not_returned = Carbon::now()->diffInDays($transaction->datetime_borrowed);
        $transaction->save();

        // Create a notification
        $notification = [
            'type' => 'damaged',
            'transaction_id' => $transaction->id,
            'user_name' => $transaction->user_name,
            'item' => $transaction->item,
            'quantity' => $transaction->quantity,
            'unit' => $transaction->unit, // Ensure 'unit' is a valid field or replace it with the correct field
            'date_returned' => Carbon::parse($transaction->datetime_returned)
        ];

        session()->push('notifications', $notification);

        return back()->with('success', 'Item marked as damaged successfully!');
    }


    public function notifyBorrower(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'integer|exists:office_requests,id',
        ]);

        foreach ($request->request_ids as $requestId) {
            $requestRecord = \App\Models\OfficeRequest::with('equipment')->findOrFail($requestId);

            if ($requestRecord->is_notified == 1 || $requestRecord->item_type !== 'Equipments') {
                continue;
            }

            $equipmentItem = $requestRecord->equipment->item;

            $borrower = \App\Models\User::findOrFail($requestRecord->requested_by);
            $message = "Your borrowed equipment '{$equipmentItem}' has exceeded the allowed return period. Please return it immediately.";
            $borrower->notify(new \App\Notifications\BorrowerNotification($message));

            $requestRecord->update(['is_notified' => 1]);
        }

        return response()->json(['message' => 'Borrowers notified successfully.']);
    }


    public function selectedItems($id)
    {
        $equipmentItems = EquipmentItems::where('equipment_id', $id)
            ->whereNotIn('status', ['Queue', 'Damaged'])
            ->get();
        return response()->json($equipmentItems);
    }



    public function submitAddedNotes(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer|exists:equipment_items,id',
            'notes' => 'required|string',
        ]);

        $itemToBeMarkAsDamaged = EquipmentItems::find($validated['item_id']);
        $itemToBeMarkAsDamaged->note = $validated['notes'];
        $itemToBeMarkAsDamaged->save();

        return response()->json(['success' => true, 'message' => 'Item notes added!'], 200);
    }


    public function submitMarkAsDamaged(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer|exists:equipment_items,id',
        ]);

        $itemToBeMarkAsDamaged = EquipmentItems::find($validated['item_id']);
        $itemToBeMarkAsDamaged->status = "Damaged";
        $itemToBeMarkAsDamaged->save();

        return response()->json(['success' => true, 'message' => 'Item marked as damaged successfully!'], 200);
    }


    public function submitGoodCondition(Request $request)
    {
        $validated = $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:equipment_items,id',
        ]);

        foreach ($validated['selected_items'] as $itemId) {
            $equipmentItem = EquipmentItems::find($itemId);
            $equipmentItem->status = 'Good';
            $equipmentItem->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Items marked as good condition successfully.']);
    }


    public function approveAllSelected(Request $request)
    {
        // Retrieve the borrowed equipment based on selected item IDs
        $borrowedEquipment = BorrowedEquipment::whereIn('equipment_serial_id', $request->input('selectedItems'))->get();
        if ($borrowedEquipment->isEmpty()) {
            return response()->json(['message' => 'No items selected.'], 400);
        }

        $officeRequisitionRequestId = $borrowedEquipment[0]->office_requests_id;
        // Approve selected items
        $equipments = [];
        foreach ($borrowedEquipment as $equipment) {
            $equipment->borrow_status = 'Approved';
            $equipment->save();
            $equipments[] = $equipment;
        }
        // // Decline unselected items
        $declinedEquipmentSerialIds = BorrowedEquipment::where('office_requests_id', $officeRequisitionRequestId)
            ->whereNotIn('equipment_serial_id', $request->input('selectedItems'))
            ->pluck('equipment_serial_id')->toArray();

        $declinedItemsSetToGood = EquipmentItems::whereIn('id', $declinedEquipmentSerialIds)->get();
        foreach ($declinedItemsSetToGood as $itemtoSetasGood) {
            $itemtoSetasGood->status = "Good";
            $itemtoSetasGood->save();
        }

        // Update the records with the 'Declined' status
        BorrowedEquipment::where('office_requests_id', $officeRequisitionRequestId)
            ->whereNotIn('equipment_serial_id', $request->input('selectedItems'))
            ->update(['borrow_status' => 'Declined']);

        $extractedEquipmentId = [];
        $equipmentSerialId = [];

        // Extract equipment and serial IDs from the approved equipments array
        foreach ($equipments as $ExtractedEquipment) {
            $extractedEquipmentId[] = $ExtractedEquipment->item_id;
            $equipmentSerialId[] = $ExtractedEquipment->equipment_serial_id;
        }


        // Get the selected equipment based on extracted equipment IDs
        $SlectedBorrowedEquipmentToApproved = Equipment::whereIn('id', $extractedEquipmentId)->get();

        // Create an associative array to match equipment ID with item name
        $itemMap = [];
        foreach ($SlectedBorrowedEquipmentToApproved as $equipment) {
            $itemMap[$equipment->id] = $equipment->item;
        }

        // Get the serial numbers based on extracted serial IDs
        $serials = EquipmentItems::whereIn('id', $equipmentSerialId)->get();
        $serialNos = [];

        // Populate the itemSelected array with item names and their respective serial numbers
        foreach ($serials as $itemSerials) {
            $serialNos[] = $itemMap[$itemSerials->equipment_id] . " (Serial No: " . $itemSerials->serial_no . ")";
        }

        // Format the serial numbers into a single string
        $formattedSerials = implode(", ", $serialNos);

        // Get the declined items based on the request
        $declinedItems = BorrowedEquipment::where('office_requests_id', $officeRequisitionRequestId)
            ->whereNotIn('item_id', $request->input('selectedItems'))
            ->get();

        $declinedItemDetails = [];
        foreach ($declinedItems as $declinedItem) {
            $equipment = Equipment::find($declinedItem->item_id);
            $itemSerial = EquipmentItems::find($declinedItem->equipment_serial_id);
            $declinedItemDetails[] = $equipment->item . " (Serial No: " . $itemSerial->serial_no . ")";
        }

        // Format the declined items into a single string
        $formattedDeclinedItems = implode(", ", $declinedItemDetails);

        // Construct the message
        $userFromRequest = OfficeRequest::find($officeRequisitionRequestId);
        $userId = $userFromRequest->requested_by;
        $user = User::find($userId);

        $message = "Hi {$user->name}, your request: {$formattedSerials} has been Approved!";

        if (!empty($formattedDeclinedItems)) {
            $message .= " The following items have been Declined: {$formattedDeclinedItems}.";
        }

        $user->notify(new ApproveOfficeMultipleRequest($message));

        return response()->json(["success" => true, 'message' => 'Selected items have been approved and others declined.']);
    }



    public function RecievedAllSelected(Request $request)
    {
        // dd($request);
        $borrowedEquipment = BorrowedEquipment::whereIn('equipment_serial_id', $request->input('selected_items'))->get();
        // dd($borrowedEquipment);
        foreach ($borrowedEquipment as $equipment) {
            $equipment->borrow_status = 'Received';
            $equipment->save();
        }

        return response()->json(["success" => true, 'message' => 'Selected items have been mark as received.']);
    }


    public function ReturnAllItems(Request $request)
    {
        // dd($request);
        $borrowedEquipment = BorrowedEquipment::where('office_requests_id', $request->input('itemRequisitionId'))->get();
        $officeRequest = OfficeRequest::find($request->input('itemRequisitionId'));
        $itemIds = [];
        foreach ($borrowedEquipment as $item) {
            $itemIds[] = $item->equipment_serial_id;
            if ($item->borrow_status !== 'Declined') {
                $item->borrow_status = 'Returned';
                $item->date_returned = now();
                $item->save();
            }
        }
        if ($officeRequest->item_type === "Equipments") {
            $serials = EquipmentItems::whereIn('id', $itemIds)->get();
            foreach ($serials as $serial) {
                $serial->status = "Good";
                $serial->save();
            }
        }

        return response()->json(["success" => true, 'message' => 'Selected items have been mark as returned.']);
    }


    public function print(Request $request)
    {
        $title = $request->input('title');
        $path = $request->input('path');
        $data = $request->input('data');
        $collection = collect($data);

        // Extract the first element (index 0) of each inner array
        $ids = $collection->pluck(0);

        // if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
        //     throw new HttpResponseException(response()->json([
        //         'message' => 'No valid data available to generate PDF.',
        //     ], 422));
        // }
        $requests = DB::table('office_requests')
            ->select(
                'office_requests.id',
                'office_requests.item_id',
                'office_requests.item_type',
                'office_requests.quantity_requested',
                'office_requests.requested_by',
                'office_requests.purpose',
                'office_requests.status',
                'office_requests.created_at',
                'office_requests.updated_at',
                'office_requests.is_notified',
                'users.id',
                'users.name as requested_by_name',
                'supplies.item as supply_item_name',
                DB::raw('GROUP_CONCAT(DISTINCT supplies_items.serial_no) as supply_serial_numbers'),

                // Use GROUP_CONCAT to combine equipment info into one string per request
                DB::raw('GROUP_CONCAT(DISTINCT equipment.item) as equipment_item_name'),
                DB::raw('GROUP_CONCAT(DISTINCT equipment_items.serial_no) as equipment_serial_numbers')
            )
            ->leftJoin('users', function ($join) {
                $join->on('office_requests.requested_by', '=', 'users.id');
            })
            ->leftJoin('supplies', function ($join) {
                $join->on('office_requests.item_id', '=', 'supplies.id')
                    ->whereNotNull('office_requests.item_id')
                    ->where('office_requests.item_type', '=', 'Supplies');
            })
            ->leftJoin('supplies_items', 'supplies.id', '=', 'supplies_items.supplies_id')

            ->leftJoin('borrowed_equipment', function ($join) {
                $join->on('office_requests.id', '=', 'borrowed_equipment.office_requests_id')
                    ->whereNull('office_requests.item_id')
                    ->where('office_requests.item_type', '=', 'Equipments');
            })
            ->leftJoin('equipment', 'borrowed_equipment.item_id', '=', 'equipment.id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->whereIn('office_requests.id', $ids)
            ->groupBy(
                'office_requests.id',
                'office_requests.item_id',
                'office_requests.item_type',
                'office_requests.quantity_requested',
                'office_requests.requested_by',
                'office_requests.purpose',
                'office_requests.status',
                'office_requests.created_at',
                'office_requests.updated_at',
                'office_requests.is_notified',
                'supplies.item',
                'users.id',
                'users.name'
            )

            ->get();

        // dd($requests);

        $pdf = Pdf::loadView("office.transactions.print", compact('requests', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('office_transaction_request_data.pdf');
        // return view('office.transactions.print', compact('requests'));
    }

    public function printAllOfficeTransac()
    {
        $title = "OFFICE TRANSACTION";
        $requests = DB::table('office_requests')
            ->select(
                'office_requests.id',
                'office_requests.item_id',
                'office_requests.item_type',
                'office_requests.quantity_requested',
                'office_requests.requested_by',
                'office_requests.purpose',
                'office_requests.status',
                'office_requests.created_at',
                'office_requests.updated_at',
                'office_requests.is_notified',
                'users.id',
                'users.name as requested_by_name',
                'supplies.item as supply_item_name',
                DB::raw('GROUP_CONCAT(DISTINCT supplies_items.serial_no) as supply_serial_numbers'),

                // Use GROUP_CONCAT to combine equipment info into one string per request
                DB::raw('GROUP_CONCAT(DISTINCT equipment.item) as equipment_item_name'),
                DB::raw('GROUP_CONCAT(DISTINCT equipment_items.serial_no) as equipment_serial_numbers')
            )
            ->leftJoin('users', function ($join) {
                $join->on('office_requests.requested_by', '=', 'users.id');
            })
            ->leftJoin('supplies', function ($join) {
                $join->on('office_requests.item_id', '=', 'supplies.id')
                    ->whereNotNull('office_requests.item_id')
                    ->where('office_requests.item_type', '=', 'Supplies');
            })
            ->leftJoin('supplies_items', 'supplies.id', '=', 'supplies_items.supplies_id')

            ->leftJoin('borrowed_equipment', function ($join) {
                $join->on('office_requests.id', '=', 'borrowed_equipment.office_requests_id')
                    ->whereNull('office_requests.item_id')
                    ->where('office_requests.item_type', '=', 'Equipments');
            })
            ->leftJoin('equipment', 'borrowed_equipment.item_id', '=', 'equipment.id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->groupBy(
                'office_requests.id',
                'office_requests.item_id',
                'office_requests.item_type',
                'office_requests.quantity_requested',
                'office_requests.requested_by',
                'office_requests.purpose',
                'office_requests.status',
                'office_requests.created_at',
                'office_requests.updated_at',
                'office_requests.is_notified',
                'supplies.item',
                'users.id',
                'users.name'
            )

            ->get();

        // dd($requests);

        $pdf = Pdf::loadView("office.transactions.print", compact('requests', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('office_transaction_request_data.pdf');
    }
}
