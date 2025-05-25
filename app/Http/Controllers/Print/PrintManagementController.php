<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\LaboratoryEquipment;
use App\Models\OfficeRequest;
use App\Models\Requisition;
use App\Models\RequisitionItemsSerial;
use App\Models\Supplies;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrintManagementController extends Controller
{
    protected function getUserAuthView()
    {
        /**
         * @var App\Models\User;
         */
        $user = Auth::user();
        if ($user->hasRole('laboratory')) {
            return 'laboratory';
        }

        if ($user->hasRole('dean')) {
            return 'dean';
        }

        if ($user->hasRole('superadmin')) {
            return 'superadmin';
        }

        if ($user->hasRole('site secretary')) {
            return 'office';
        }
    }

    public function store(Request $request)
    {
        $viewPath = self::getUserAuthView();
        $data = $request->input('data');
        $title = $request->input('title');
        $path = $request->input('path');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $pdf = Pdf::loadView("{$viewPath}.{$path}.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function generateAll(Request $request)
    {
        $viewPath = self::getUserAuthView();
        $title = $request->input('title');
        $path = $request->input('path');
        $category = $request->input('category');
        $equipments = LaboratoryEquipment::where('category_id', $category)->get();
        $data = $equipments->map(function ($item) {
            return [
                $item->id,
                $item->equipment,
                $item->brand,
                $item->quantity,
                $item->unit,
                date('F d, Y', strtotime($item->date_acquired)),
            ];
        });



        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        // dd("{$viewPath}.{$path}");
        $pdf = Pdf::loadView("{$viewPath}.{$path}.print-all", ['data' => $data, 'title' => $title])->setPaper('A4', 'landscape');
        return $pdf->stream('full_inventory_report.pdf');
    }


    public function printSupplies(Request $request)
    {
        $viewPath = self::getUserAuthView();
        $data = $request->input('data');
        $title = $request->input('title');
        $path = $request->input('path');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $pdf = Pdf::loadView("{$viewPath}.{$path}.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function printAllSupplies(Request $request)
    {
        $viewPath = self::getUserAuthView();
        $title = $request->input('title');
        $path = $request->input('path');
        $supplies = Supplies::all();
        $data = $supplies->map(function ($item) {
            return [
                $item->id,
                $item->quantity,
                $item->unit,
                $item->item,
                $item->brand_description,
                $item->location,
                date('F d, Y', strtotime($item->date_delivered)),
            ];
        });

        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        $pdf = Pdf::loadView("{$viewPath}.{$path}.print-all", ['data' => $data, 'title' => $title])->setPaper('A4', 'landscape');
        return $pdf->stream('full_inventory_report.pdf');
    }


    public function printEquipments(Request $request)
    {
        $viewPath = self::getUserAuthView();
        $title = $request->input('title');
        $path = $request->input('path');
        $data = $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $pdf = Pdf::loadView("{$viewPath}.{$path}.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function printAllEquipments(Request $request)
    {
        $viewPath = self::getUserAuthView();
        $title = $request->input('title');
        $path = $request->input('path');
        $equipments = Equipment::all();
        $data = $equipments->map(function ($item) {
            return [
                $item->id,
                $item->quantity,
                $item->unit,
                $item->item,
                $item->brand_description,
                $item->location,
                date('F d, Y', strtotime($item->date_delivered)),
            ];
        });

        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $pdf = Pdf::loadView("{$viewPath}.{$path}.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }


    public function siteReports(Request $request)
    {
        $title = $request->input('title');
        $data = $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        $pdf = Pdf::loadView("reports.dean-print-equipment-reports", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function AllSiteEquipmentReports(Request $request)
    {
        $title = $request->input('title');
        $items = OfficeRequest::select(
            'office_requests.*',
            'borrowed_equipment.office_requests_id',
            'borrowed_equipment.borrow_status',
            'equipment.item as equipment_item',
            'equipment_items.serial_no as equipment_serial_no',
            'equipment_items.note as equipment_notes',
            'borrowed_equipment.date_returned',
            'borrowed_equipment.item_id',
            'borrowed_equipment.equipment_serial_id',
            'equipment_items.equipment_id',
            'equipment_items.status as item_status',
            'equipment.*',
            'users.name as request_by',
            'office_requests.created_at as date_added'
        )
            ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->leftJoin('equipment', 'equipment_items.equipment_id', '=', 'equipment.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Equipments')
            ->where('equipment_items.status', 'Damaged')
            ->get();

        foreach ($items as $index => $item) {
            $item->serial_no_count = $index + 1;
        }


        $pdf = Pdf::loadView("reports.site.print-all-damage", compact('items', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }


    public function siteLostDamageReports(Request $request)
    {
        $title = $request->input('title');
        $data = $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        $pdf = Pdf::loadView("reports.dean-print-equipment-reports", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function siteOfficeTransactionsPrintAllDamage(Request $request)
    {
        $title = $request->input('title');
        $data = OfficeRequest::select(
            'office_requests.*',
            'borrowed_equipment.office_requests_id',
            'borrowed_equipment.borrow_status',
            'equipment.item as equipment_item',
            'equipment_items.serial_no as equipment_serial_no',
            'equipment_items.note as equipment_notes',
            'borrowed_equipment.date_returned',
            'borrowed_equipment.item_id',
            'borrowed_equipment.equipment_serial_id',
            'equipment_items.equipment_id',
            'equipment_items.status as item_status',
            'equipment.*',
            'users.name as request_by',
            'office_requests.created_at as date_added'
        )
            ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->leftJoin('equipment', 'equipment_items.equipment_id', '=', 'equipment.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Equipments')
            ->where('equipment_items.status', 'Damaged')
            ->get();

        // dd($data);

        if (empty($data)) {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        // dd($data);

        $pdf = Pdf::loadView("reports.dean-print-damage-equipment-reports", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function printLabReport(Request $request)
    {
        $data = $request->input('data');
        $title = $request->input('title');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        $pdf = Pdf::loadView("reports.laboratory-print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }

    public function printAllLabReport(Request $request)
    {
        $count = RequisitionItemsSerial::count();

        $title = $request->input('title');
        $items = RequisitionItemsSerial::whereHas('serialRelatedItem', function ($query) {
            $query->where('condition', 'Damaged');
        })
            ->with(['equipmentBelongs.category', 'serialRelatedItem'])
            ->get();

        $data = $items->map(function ($item) {
            return [
                $item->equipmentBelongs->equipment . " - Serial: " . $item->serialRelatedItem->serial_no,
                $item->equipmentBelongs->category->name,
                $item->borrow_status,
                $item->serialRelatedItem->notes,
                date('F d, Y h:i A', strtotime($item->serialRelatedItem->noted_at))
            ];
        });

        if (empty($data)) {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        $pdf = Pdf::loadView("reports.laboratory-print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }


    public function requsitionPrint(Request $request)
    {
        $data = $request->input('data');
        $title = $request->input('title');

        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $pdf = Pdf::loadView("reports.requisition.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('visible_equipment.pdf');
    }


    public function printAllRequisitionReports(Request $request)
    {
        // Fetch requisition data along with its related models
        $data = Requisition::with([
            'category',
            'items.serials.equipmentBelongs',
            'students',
            'instructor'
        ])->get();

        // Title passed from the request
        $title = $request->input('title');

        // Check if data is empty
        if ($data->isEmpty()) {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }

        // Map the requisition data for the table
        $items = $data->map(function ($requisition) {
            return [
                $requisition->activity, // Activity
                $requisition->category->name, // Category name
                $requisition->items->map(function ($item) {
                    return $item->serials->map(function ($serial) {
                        return $serial->equipmentBelongs->equipment . ' - ' . $serial->serialRelatedItem->serial_no; // Equipment name and serial
                    })->join(', '); // Join if multiple items
                })->join(', '), // Join all items if multiple
                $requisition->course_year, // Course Year
                $requisition->instructor->name, // Instructor name
                $requisition->students->map(function ($student) {
                    return $student->student_name;
                })->join(', '), // Join the names with commas
                $requisition->status, // Status
                $requisition->subject, // Subject
                $requisition->date_time_filed, // Date Filed
            ];
        });


        $pdf = Pdf::loadView("reports.requisition.print-all", compact('items', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }


    public function siteOfficeTransactionsPrint(Request $request)
    {
        $data =  $request->input('data');
        $title = $request->input('title');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.site.print.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }

    public function siteOfficeTransactionsPrintAll(Request $request)
    {
        $title = "SITE TRANSACTION EQUIPMENT REPORTS";

        $data = OfficeRequest::selectRaw('
                office_requests.id,
                MAX(users.name) as request_by,
                MAX(equipment.item) as equipment_item,
                GROUP_CONCAT(equipment_items.serial_no) as serial_numbers,
                MAX(office_requests.quantity_requested) as quantity_requested,
                MAX(office_requests.purpose) as purpose,
                MAX(office_requests.created_at) as date_added
            ')
            ->leftJoin('borrowed_equipment', 'office_requests.id', '=', 'borrowed_equipment.office_requests_id')
            ->leftJoin('equipment_items', 'borrowed_equipment.equipment_serial_id', '=', 'equipment_items.id')
            ->leftJoin('equipment', 'equipment_items.equipment_id', '=', 'equipment.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Equipments')
            ->groupBy('office_requests.id')
            ->get();
        // dd($data);
        $pdf = Pdf::loadView("reports.site.transactions.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }

    public function suppliesReportPrint(Request $request)
    {
        $title = $request->input('title');
        $items = OfficeRequest::select(
            'office_requests.*',
            'supplies.item',
            'office_requests.created_at as date_added',
            'users.name as request_by',
        )
            ->leftJoin('supplies', 'office_requests.item_id', '=', 'supplies.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Supplies')
            ->get();
        $data = $items->map(function ($item) {
            return [
                $item->item,
                $item->quantity_requested,
                $item->request_by,
                $item->purpose,
                date('F d, Y h:i A', strtotime($item->created_at))
            ];
        });


        $pdf = Pdf::loadView("reports.site.print.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }


    public function suppliesReportPrintAll(Request $request)
    {
        $title = $request->input('title');
        $items = OfficeRequest::select(
            'office_requests.*',
            'supplies.item',
            'office_requests.created_at as date_added',
            'users.name as request_by',
        )
            ->leftJoin('supplies', 'office_requests.item_id', '=', 'supplies.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->where('office_requests.item_type', 'Supplies')
            ->get();
        $data = $items->map(function ($item) {
            return [
                $item->item,
                $item->quantity_requested,
                $item->request_by,
                $item->purpose,
                date('F d, Y h:i A', strtotime($item->created_at))
            ];
        });


        $pdf = Pdf::loadView("reports.site.print.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }


    public function transactionReportPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.site.print.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }


    public function labTransacPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.laboratory.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }

    public function labTransacPrintAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.laboratory.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }


    public function labFluidPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.laboratory.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }

    public function  labFluidPrintAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.laboratory.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('requisition_report.pdf');
    }

    public function deanlabTransacPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.dean.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }

    public function deanlabTransacPrintAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.dean.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }


    public function deanlabEquipmentPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("dean.print.equipment-print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }

    public function deanlabEquipmentPrintAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("dean.print.equipment-print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }

    public function printRequisitionReport(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("dean.print.requisition-print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }

    public function printRequisitionReportAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("dean.print.requisition-print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }


    public function deanSuppliesPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("dean.print.supplies-print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_office_supplies.pdf');
    }

    public function deanSuppliesPrintAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("dean.print.supplies-print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_office_supplies.pdf');
    }



    public function adminlabEquipmentPrint(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.admin.print", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }

    public function adminlabEquipmentPrintAll(Request $request)
    {
        $data =  $request->input('data');
        if (empty($data) || !isset($data[0][0]) || $data[0][0] === "No data available in table") {
            throw new HttpResponseException(response()->json([
                'message' => 'No valid data available to generate PDF.',
            ], 422));
        }
        $title = $request->input('title');

        $pdf = Pdf::loadView("reports.admin.print-all", compact('data', 'title'))->setPaper('A4', 'landscape');
        return $pdf->stream('dean_laboratory_transaction.pdf');
    }
}
