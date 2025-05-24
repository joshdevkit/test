<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ComputerEngineering;
use App\Models\ComputerEngineeringSerial;
use App\Models\Construction;
use App\Models\ConstructionSerials;
use App\Models\Fluid;
use App\Models\FluidSerials;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentItem;
use App\Models\OfficeRequest;
use App\Models\Requisition;
use App\Models\RequisitionItemsSerial;
use App\Models\RequisitionsItems;
use App\Models\RequisitionsItemsStudents;
use App\Models\Surveying;
use App\Models\SurveyingSerials;
use App\Models\Testing;
use App\Models\TeacherBorrow;
use App\Models\TestingSerials;
use App\Models\User;
use App\Notifications\BorrowerNotification;
use App\Notifications\DeanRequisitionDecisionNotification;
use App\Notifications\NewRequisitionNotification;
use App\Notifications\RequisitionDecisionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class TeacherBorrowController extends Controller
{

    public function print($id)
    {
        $data = Requisition::with([
            'students',
            'category',
            'instructor',
            'items.serials' => function ($query) {
                $query->whereIn('borrow_status', ['Approved', 'Received']);
            },
            'items.serials.equipmentBelongs',
            'items.serials.serialRelatedItem'
        ])->find($id);

        return view('laboratory.print', compact('data'));
    }

    public function selectCategory(Request $request)
    {
        $category = $request->input('category');
        if ($category == 'General Construction') {
            $items = LaboratoryEquipment::with(['items' => function ($query) {
                $query->whereNotIn('condition', ['Damaged', 'Queue']);
            }, 'category'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'General Construction');
                })
                ->get()
                ->map(function ($constructionsSerial) {
                    return [
                        'id' => $constructionsSerial->id,
                        'count' => $constructionsSerial->items->whereNotIn('condition', ['Damaged', 'Queue'])->count(),
                        'brand' => $constructionsSerial->brand,
                        'equipment' => $constructionsSerial->equipment,
                    ];
                })
                ->toArray();
        } elseif ($category == 'Testing & Mechanics') {
            $items = LaboratoryEquipment::with(['items' => function ($query) {
                $query->whereNotIn('condition', ['Damaged', 'Queue']);
            }, 'category'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Testing & Mechanics');
                })
                ->get()
                ->map(function ($constructionsSerial) {
                    return [
                        'id' => $constructionsSerial->id,
                        'count' => $constructionsSerial->items->whereNotIn('condition', ['Damaged', 'Queue'])->count(),
                        'brand' => $constructionsSerial->brand,
                        'equipment' => $constructionsSerial->equipment,
                    ];
                })
                ->toArray();
        } elseif ($category == 'Surveying') {
            $items = LaboratoryEquipment::with(['items' => function ($query) {
                $query->whereNotIn('condition', ['Damaged', 'Queue']);
            }, 'category'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Surveying');
                })
                ->get()
                ->map(function ($constructionsSerial) {
                    return [
                        'id' => $constructionsSerial->id,
                        'count' => $constructionsSerial->items->whereNotIn('condition', ['Damaged', 'Queue'])->count(),
                        'brand' => $constructionsSerial->brand,
                        'equipment' => $constructionsSerial->equipment,
                    ];
                })
                ->toArray();
        } elseif ($category == 'Hydraulics and Fluids') {
            $items = LaboratoryEquipment::with(['items' => function ($query) {
                $query->whereNotIn('condition', ['Damaged', 'Queue']);
            }, 'category'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Hydraulics and Fluids');
                })
                ->get()
                ->map(function ($constructionsSerial) {
                    return [
                        'id' => $constructionsSerial->id,
                        'count' => $constructionsSerial->items->whereNotIn('condition', ['Damaged', 'Queue'])->count(),
                        'brand' => $constructionsSerial->brand,
                        'equipment' => $constructionsSerial->equipment,
                    ];
                })
                ->toArray();
        } elseif ($category == 'Computer Engineering') {
            $items = LaboratoryEquipment::with(['items' => function ($query) {
                $query->whereNotIn('condition', ['Damaged', 'Queue']);
            }, 'category'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Computer Engineering');
                })
                ->get()
                ->map(function ($constructionsSerial) {
                    return [
                        'id' => $constructionsSerial->id,
                        'count' => $constructionsSerial->items->whereNotIn('condition', ['Damaged', 'Queue'])->count(),
                        'brand' => $constructionsSerial->brand,
                        'equipment' => $constructionsSerial->equipment,
                    ];
                })
                ->toArray();
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
        $user = Auth::user();

        return view('profile.teachersborrow.create', compact('user'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $category = Category::where('name', $request->input('category'))->first();
        $validated = $request->validate([
            'dateFiled' => 'required|date',
            'dateNeeded' =>  'required|date',
            'subject' => 'required|string',
            'courseYear' => 'required|string',
            'activityTitle' => 'required|string',
            'items.*.item_id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
            'items.*.remarks' => 'required|string',
            'students' => 'required|array',
            'students.*' => 'string',
        ]);

        $instructorId = Auth::id();
        $requisition = Requisition::create([
            'category_id' => $category->id,
            'date_time_filed' => $validated['dateFiled'],
            'date_time_needed' => $validated['dateNeeded'],
            'instructor_id' => $instructorId,
            'subject' => $validated['subject'],
            'course_year' => $validated['courseYear'],
            'activity' => $validated['activityTitle'],
        ]);
        $nestedArrayIds = [];
        foreach ($request->input('items') as $item) {
            $nestedArrayIds[] = $item["item_id"];
            if (isset($item["items"]) && is_array($item["items"])) {
                foreach ($item["items"] as $nestedItem) {
                    if (!is_array($nestedItem)) {
                        $nestedArrayIds[] = $nestedItem;
                    }
                }
            }
        }

        // Insert into RequisitionsItems and RequisitionItemsSerial
        foreach ($request->input('items') as $item) {
            $requisitionItem = RequisitionsItems::create([
                'requisition_id' => $requisition->id,
                'quantity' => $item['quantity'],
            ]);

            foreach ($item['items'] as $nestedItem) {
                if (!is_array($nestedItem)) {
                    RequisitionItemsSerial::create([
                        'requisition_items_id' => $requisitionItem->id,
                        'equipment_id' => $item['item_id'], // Use parent item's item_id as equipment_id
                        'equipment_serial_id' => $nestedItem, // Use the nested item value as equipment_serial_id
                        'condition_during_borrow' => $item['remarks']
                    ]);

                    $laboratoryEquipmentItem = LaboratoryEquipmentItem::where('id', $nestedItem)->first();
                    if ($laboratoryEquipmentItem) {
                        $laboratoryEquipmentItem->condition = 'Queue';
                        $laboratoryEquipmentItem->save();
                    }
                }
            }
        }

        foreach ($validated['students'] as $student) {
            RequisitionsItemsStudents::create([
                'requisition_id' => $requisition->id,
                'student_name' => $student,
            ]);
        }

        $laboratoryUsers = User::role('laboratory')->get();
        foreach ($laboratoryUsers as $user) {
            $user->notify(new NewRequisitionNotification($requisition));
        }

        return redirect()->route('teachersborrow.create')->with('success', 'Requisitions request has been submitted to Laboratory.');
    }



    public function index()
    {
        $requisitions = Requisition::with([
            'students',
            'category',
            'instructor',
            'items.serials.equipmentBelongs',
            'items.serials.serialRelatedItem'
        ])
            ->latest()
            ->get();
        // dd($requisitions);


        // $teacherborrows = TeacherBorrow::all();
        // $notifications = Auth::user()->notifications;

        // // dd($requisitions);
        /**
         * @var App\Models\User;
         */
        $user = Auth::user();

        // dd($requisitions);
        if ($user->hasRole('laboratory')) {
            return view('laboratory.transaction.index', compact('requisitions'));
        } else {
            return view('superadmin.transactions', compact('requisitions'));
        }
    }

    public function print_transaction()
    {
        $requisitions = Requisition::with([
            'students',
            'category',
            'instructor',
            'items.serials.equipmentBelongs',
            'items.serials.serialRelatedItem'
        ])
            ->latest()
            ->get();
        // dd($requisitions);

        $pdf = Pdf::loadView("laboratory.transaction.print", compact('requisitions'))->setPaper('A4', 'landscape');
        return $pdf->stream('laboratory_transaction_data.pdf');
    }

    public function dean_index()
    {
        $notifications = Auth::user()->notifications;

        $requisitions = Requisition::with([
            'students',
            'category',
            'instructor',
            'items.serials.equipmentBelongs',
            'items.serials.serialRelatedItem'
        ])
            ->latest()
            ->get();
        // dd($requisitions);
        return view('dean.transaction.index', compact('notifications', 'requisitions'));
    }

    public function retrieve($id)
    {
        $data = Requisition::with([
            'students',
            'category',
            'instructor',
            'items.serials.equipmentBelongs',
            'items.serials.serialRelatedItem'
        ])->find($id);
        // dd($data);

        return view('laboratory.transaction.details', compact('data', 'id'));
    }

    public function show_data($id)
    {
        $data = Requisition::with([
            'students',
            'category',
            'instructor',
            'items.serials.equipmentBelongs',
            'items.serials.serialRelatedItem'
        ])->find($id);

        // dd($data);

        return view('dean.transaction.details', compact('data'));
    }

    public function findMatchingItems(Request $request)
    {
        // dd($request);
        $category = $request->input('category');
        $equipment_id = $request->input('request_id');

        $data =  $this->getItemsByIdOnly($category, $equipment_id);
        return response()->json([
            $data
        ]);
    }

    protected function getItemsByIdOnly($category, $equipment_id)
    {
        switch ($category) {
            case 'General Construction':
                return LaboratoryEquipmentItem::where('laboratory_equipment_id', $equipment_id)
                    ->whereNotIn('condition', ['Queue', 'Damaged'])
                    ->get();
            case 'Testing & Mechanics':
                return LaboratoryEquipmentItem::where('laboratory_equipment_id', $equipment_id)
                    ->whereNotIn('condition', ['Queue', 'Damaged'])
                    ->get();
            case 'Surveying':
                return LaboratoryEquipmentItem::where('laboratory_equipment_id', $equipment_id)
                    ->whereNotIn('condition', ['Queue', 'Damaged'])
                    ->get();
            case 'Hydraulics and Fluids':
                return LaboratoryEquipmentItem::where('laboratory_equipment_id', $equipment_id)
                    ->whereNotIn('condition', ['Queue', 'Damaged'])
                    ->get();
            case 'Computer Engineering':
                return LaboratoryEquipmentItem::where('laboratory_equipment_id', $equipment_id)
                    ->whereNotIn('condition', ['Queue', 'Damaged'])
                    ->get();
            default:
                return collect();
        }
    }

    protected function getItemsByCategory($category, $equipment_ids)
    {
        switch ($category) {
            case 'Constructions':
                return DB::table('construction')
                    ->leftJoin('construction_serials', 'construction.id', '=', 'construction_serials.product_id')
                    ->whereIn('construction.id', $equipment_ids)
                    ->select('*')
                    ->get();
            case 'Testings':
                return DB::table('testings')
                    ->leftJoin('testing_serials', 'testings.id', '=', 'testing_serials.product_id')
                    ->whereIn('testings.id', $equipment_ids)
                    ->select('*')
                    ->get();
            case 'Surveyings':
                return DB::table('surveyings')
                    ->leftJoin('surveying_serials', 'surveyings.id', '=', 'surveying_serials.product_id')
                    ->whereIn('surveyings.id', $equipment_ids)
                    ->select('*')
                    ->get();
            case 'Fluids':
                return DB::table('fluid')
                    ->leftJoin('fluid_serials', 'fluid.id', '=', 'fluid_serials.product_id')
                    ->whereIn('fluid.id', $equipment_ids)
                    ->select('*')
                    ->get();
            case 'ComputerEngineering':
                return DB::table('computer_engineering')
                    ->leftJoin('computer_engineering_serials', 'computer_engineering.id', '=', 'computer_engineering_serials.product_id')
                    ->whereIn('computer_engineering.id', $equipment_ids)
                    ->select('*')
                    ->get();
            default:
                return collect();
        }
    }

    protected function getStudentsByRequisitionId($id)
    {
        return DB::table('requisitions_items_students')
            ->join('requisitions', 'requisitions_items_students.requisition_id', '=', 'requisitions.id')
            ->select(
                'requisitions_items_students.student_name',
                'requisitions.course_year',
                'requisitions.status'
            )
            ->where('requisitions_items_students.requisition_id', $id)
            ->get();
    }

    public function decision(Request $request)
    {
        $deanUsers = User::role('dean')->get();
        $validated = $request->validate([
            'requisition_id' => 'required|integer',
            'signature' => 'nullable',
            'feedback' => 'string|nullable'
        ]);


        if (!empty($validated['feedback'])) {
            $data = Requisition::find($request->requisition_id);
            $data->status = 'Declined';
            $data->reason_for_decline = $validated['feedback'];
            $data->save();


            $requisitionItems = RequisitionsItems::where('requisition_id', $data->id)->first();
            $requestSerials = RequisitionItemsSerial::where("requisition_items_id", $requisitionItems->id)->get();
            $equipment_serial_id = [];
            foreach ($requestSerials as $serial) {
                $serial->borrow_status = 'Declined';
                $serial->save();
                $equipment_serial_id[] = $serial->equipment_serial_id;
            }

            $equipmentSerials = LaboratoryEquipmentItem::whereIn('id', $equipment_serial_id)->get();
            foreach ($equipmentSerials as $serialData) {
                $serialData->condition = "Good";
                $serialData->save();
            }

            $userFromRequest = User::find($data->instructor_id);
            $userFromRequest->notify(new RequisitionDecisionNotification($data,  $data->reason_for_decline));
            foreach ($deanUsers as $dean) {
                $dean->notify(new RequisitionDecisionNotification($data, 'Declined by our Dean'));
            }
            return redirect()->back()->with('message', 'Requisition has been declined.');
        }

        $signatureData = $validated['signature'];

        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);

        $signature = base64_decode($signatureData);

        $fileName = 'signature_' . time() . '.png';

        $filePath = public_path('signatures/' . $fileName);

        file_put_contents($filePath, $signature);

        $data = Requisition::find($request->requisition_id);
        $data->labtext_signature = 'signatures/' . $fileName;
        $data->status = 'Approved and Prepared';
        $data->save();

        foreach ($deanUsers as $dean) {
            $dean->notify(new RequisitionDecisionNotification($data, 'Laboratory has Approved and Prepared a requisition request.'));
        }
        return redirect()->back()->with('message', 'Requisition has been approved');
    }


    public function dean_decision(Request $request)
    {
        $validated = $request->validate([
            'requisition_id' => 'required|integer',
            'signature' => 'nullable',
        ]);

        $signatureData = $validated['signature'];

        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);

        $signature = base64_decode($signatureData);

        $fileName = 'signature_' . time() . '.png';

        $filePath = public_path('signatures/' . $fileName);

        file_put_contents($filePath, $signature);

        $data = Requisition::find($request->requisition_id);
        $data->dean_signature = 'signatures/' . $fileName;
        $data->status = 'Accepted by Dean';
        $data->save();


        $laboratoryUsers = User::role('laboratory')->get();

        foreach ($laboratoryUsers as $user) {
            $user->notify(new DeanRequisitionDecisionNotification($data));
        }

        return redirect()->back()->with('message', 'Requisition has been approved');
    }


    public function return_damaged(Request $request)
    {
        $data = Requisition::find($request->input('id'));
        $requisitionItems = RequisitionsItems::where('requisition_id', $data->id)->first();
        $models = [
            'Constructions' => Construction::class,
            'Testings' => Testing::class,
            'ComputerEngineering' => ComputerEngineering::class,
            'Fluids' => Fluid::class,
            'Surveyings' => Surveying::class,
        ];

        if (!isset($models[$data->category])) {
            return response()->json(['error' => "No model found for {$data->category}"], 400);
        }

        $modelClass = $models[$data->category];
        $requisitionItem = $modelClass::find($requisitionItems->equipment_id);

        if (!$requisitionItem) {
            return response()->json(['error' => "Item not found for {$data->category}"], 404);
        }

        if ($request->input('status') === "Received") {
            $data->status = "Received";
            $requisitionItem->quantity -= $requisitionItems->quantity;
            $requisitionItem->save();
        }

        if ($request->input('status') === "Returned" || $request->input('status') === "Repaired") {
            $data->status = "Returned";
            $requisitionItem->quantity += $requisitionItems->quantity;
            $data->returned_date = now();
            $requisitionItem->save();
        }

        if ($request->input('status') === "Damaged") {
            $data->status = "Damaged";
            $requisitionItem->save();
        }


        if ($request->input('status') === "XXX") {
            $data->status = "XXX";
            $requisitionItem->save();
        }


        $data->save();


        return response()->json(['success' => true]);
    }



    public function approve($id)
    {
        $teacherborrow = TeacherBorrow::find($id);

        $construction = Construction::where('item', $teacherborrow->item)->first();
        $testing = Testing::where('item', $teacherborrow->item)->first();
        $surveying = Surveying::where('item', $teacherborrow->item)->first();
        $fluid = Fluid::where('item', $teacherborrow->item)->first();
        $computer = ComputerEngineering::where('item', $teacherborrow->item)->first();

        if ($construction && $construction->quantity >= $teacherborrow->quantity) {
            $construction->quantity -= $teacherborrow->quantity;
            $construction->save();
        } elseif ($testing && $testing->quantity >= $teacherborrow->quantity) {
            $testing->quantity -= $teacherborrow->quantity;
            $testing->save();
        } elseif ($surveying && $surveying->quantity >= $teacherborrow->quantity) {
            $surveying->quantity -= $teacherborrow->quantity;
            $surveying->save();
        } elseif ($fluid && $fluid->quantity >= $teacherborrow->quantity) {
            // Update the equipment quantity
            $fluid->quantity -= $teacherborrow->quantity;
            $fluid->save();
        } elseif ($computer && $computer->quantity >= $teacherborrow->quantity) {
            // Update the equipment quantity
            $computer->quantity -= $teacherborrow->quantity;
            $computer->save();
        } else {
            // Return an error message if the quantity is insufficient
            return back()->with('error', 'Insufficient quantity available for the requested item.');
        }

        // Approve the transaction
        $teacherborrow->status = 'approved';
        $teacherborrow->save();

        // Remove the corresponding notification from the session
        $this->removeNotificationByTransactionId($id);

        return back()->with('success', 'Request approved successfully!');
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
        $teacherborrow = TeacherBorrow::find($id);
        $teacherborrow->status = 'disapproved';
        $teacherborrow->save();

        // Remove the corresponding notification from the session
        $this->removeNotificationByTransactionId($id);

        return back()->with('success', 'Request disapproved successfully!');
    }

    public function returned($id)
    {
        $teacherborrow = TeacherBorrow::find($id);

        if ($teacherborrow->status == 'returned') {
            return back()->with('error', 'This item has already been marked as returned.');
        }

        $teacherborrow->status = 'returned';
        $teacherborrow->datetime_returned = Carbon::now();
        $teacherborrow->days_not_returned = Carbon::now()->diffInDays($teacherborrow->datetime_borrowed);
        $teacherborrow->save();

        // Fetch the item from the supplies or equipment table
        $construction = Construction::where('item', $teacherborrow->item)->first();
        $testing = Testing::where('item', $teacherborrow->item)->first();
        $surveying = Surveying::where('item', $teacherborrow->item)->first();
        $fluid = Fluid::where('item', $teacherborrow->item)->first();
        $computer = ComputerEngineering::where('item', $teacherborrow->item)->first();

        if ($construction) {
            // Update the supply quantity
            $construction->quantity += $teacherborrow->quantity;
            $construction->save();
        } elseif ($testing) {
            // Update the equipment quantity
            $testing->quantity += $teacherborrow->quantity;
            $testing->save();
        } elseif ($surveying) {
            // Update the equipment quantity
            $surveying->quantity += $teacherborrow->quantity;
            $surveying->save();
        } elseif ($fluid) {
            // Update the equipment quantity
            $fluid->quantity += $teacherborrow->quantity;
            $fluid->save();
        } elseif ($computer) {
            // Update the equipment quantity
            $computer->quantity += $teacherborrow->quantity;
            $computer->save();
        }

        return back()->with('success', 'Item marked as returned successfully!');
    }

    public function damaged($id)
    {
        $teacherborrow = TeacherBorrow::find($id);

        if ($teacherborrow->status == 'damaged') {
            return back()->with('error', 'This item has already been marked as damaged.');
        }

        $teacherborrow->status = 'damaged';
        $teacherborrow->datetime_returned = Carbon::now();
        $teacherborrow->days_not_returned = Carbon::now()->diffInDays($teacherborrow->datetime_borrowed);
        $teacherborrow->save();

        // Create a notification
        $notification = [
            'type' => 'damaged',
            'transaction_id' => $teacherborrow->id,
            'user_name' => $teacherborrow->user_name,
            'item' => $teacherborrow->item,
            'quantity' => $teacherborrow->quantity,
            'unit' => $teacherborrow->unit, // Ensure 'unit' is a valid field or replace it with the correct field
            'date_returned' => Carbon::parse($teacherborrow->datetime_returned)
        ];

        session()->push('notifications', $notification);

        return back()->with('success', 'Item marked as damaged successfully!');
    }


    public function getChartData()
    {
        $categories = Requisition::select('requisitions.category_id', 'categories.name', DB::raw('count(*) as count'))
            ->leftJoin('categories', 'requisitions.category_id', '=', 'categories.id')
            ->groupBy('requisitions.category_id', 'categories.name')
            ->get();

        $data = $categories->pluck('count', 'name');

        return response()->json($data);
    }



    public function offcieChartData()
    {
        $officeRequests = OfficeRequest::select('item_type', DB::raw('count(*) as count'))
            ->groupBy('item_type')
            ->get();

        $data = $officeRequests->pluck('count', 'item_type');

        return response()->json($data);
    }


    public function approve_selected(Request $request)
    {
        $requisitionId = $request->input('requisitionId');
        $selectedIds = $request->input('selectedIds');
        // Update statuses for selected and non-selected items
        RequisitionItemsSerial::whereIn('id', $selectedIds)->update(['borrow_status' => 'Approved']);
        RequisitionItemsSerial::whereNotIn('id', $selectedIds)->where('borrow_status', 'Pending')->update(['borrow_status' => 'Declined']);
        $setToGood =  RequisitionItemsSerial::whereNotIn('id', $selectedIds)->pluck('equipment_serial_id')->toArray();

        $updateDeclinetoGood = LaboratoryEquipmentItem::whereIn('id', $setToGood)->get();

        foreach ($updateDeclinetoGood as $returnAsGood) {
            $returnAsGood->condition = "Good";
            $returnAsGood->save();
        }

        // Retrieve the requisition items and details
        $requisitionItems = RequisitionsItems::where('requisition_id', $requisitionId)->first();

        $approvedDetails = RequisitionItemsSerial::with(['equipmentBelongs', 'serialRelatedItem'])
            ->where('borrow_status', 'Approved')
            ->where('requisition_items_id', $requisitionItems->id)
            ->get();

        $declinedDetails = RequisitionItemsSerial::with(['equipmentBelongs', 'serialRelatedItem'])
            ->where('borrow_status', 'Declined')
            ->where('requisition_items_id', $requisitionItems->id)
            ->get();

        $requisition = Requisition::find($requisitionId);

        // Initialize the notification message
        $message = '';

        // Append approved items to the message
        foreach ($approvedDetails as $approved) {
            $message .= "Equipment: {$approved->equipmentBelongs->equipment} - Serial: {$approved->serialRelatedItem->serial_no} has been approved by Laboratory.\n";
        }

        // Append declined items to the message only if there are any
        if ($declinedDetails->isNotEmpty()) {
            foreach ($declinedDetails as $declined) {
                $message .= "Equipment: {$declined->equipmentBelongs->equipment} - Serial: {$declined->serialRelatedItem->serial_no} has been declined by Laboratory.\n";
            }
        }

        // Notify the instructor
        $user = User::find($requisition->instructor_id);
        $user->notify(new BorrowerNotification($message));

        return response()->json([
            'success' => true,
            'message' => 'Selected items approved and non-selected items declined successfully.',
            // 'approvedDetails' => $approvedDetails,
            // 'declinedDetails' => $declinedDetails
        ]);
    }


    public function item_received(Request $request)
    {
        $requisitionItems = RequisitionsItems::where('requisition_id', $request->input('requisitionId'))->first();

        $receivedItem = RequisitionItemsSerial::where('borrow_status', 'Approved')
            ->where('requisition_items_id', $requisitionItems->id)
            ->get();

        if ($receivedItem->isNotEmpty()) {
            $receivedItem->each(function ($serial) {
                $serial->borrow_status = 'Received';
                $serial->save();
            });

            return response()->json([
                'success' => true,
                'message' => "Requisition Items have been marked as received."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "No approved items found to mark as received."
        ]);
    }


    public function item_damaged(Request $request)
    {
        $requestItemId = $request->input('selectedId');
        $item = LaboratoryEquipmentItem::find($requestItemId);
        $item->condition = "Damaged";
        $item->save();

        return response()->json([
            'success' => true,
            "Items has been mark as damage"
        ]);
    }


    public function item_notes(Request $request)
    {
        $item_id = $request->input('item_id');
        $notes = $request->input('notes');

        $item = LaboratoryEquipmentItem::find($item_id);
        $item->notes = $notes;
        $item->noted_at = now();
        $item->save();

        return response()->json([
            'success' => true,
            "Notes added successfully"
        ]);
    }

    public function item_returned(Request $request)
    {
        $requisitionItems = RequisitionsItems::where('requisition_id', $request->input('requisitionId'))->first();

        $receivedItem = RequisitionItemsSerial::where('borrow_status', 'Received')
            ->where('requisition_items_id', $requisitionItems->id)
            ->get();

        $equipment_serial_id = [];
        foreach ($receivedItem as $items) {
            $equipment_serial_id[] = $items->equipment_serial_id;
        }

        $labItems = LaboratoryEquipmentItem::where('id', $equipment_serial_id)->get();

        $requisitions = Requisition::find($requisitionItems->requisition_id);

        if ($receivedItem->isNotEmpty()) {

            $requisitions->returned_date = now();
            $requisitions->status = "Returned";
            $requisitions->save();

            foreach ($labItems as $labSerials) {
                $labSerials->condition = "Good";
                $labSerials->save();
            }

            $receivedItem->each(function ($serial) {
                $serial->borrow_status = 'Returned';
                $serial->save();
            });

            return response()->json([
                'success' => true,
                'message' => "Requisition Items have been marked as returned."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "No approved items found to mark as returned."
        ]);
    }
}
