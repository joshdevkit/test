<?php

namespace App\Http\Controllers;

use App\Models\OfficeRequisition;
use App\Models\OfficeRequisitionItems;
use App\Models\Supplies;
use App\Models\User;
use App\Notifications\NewOfficeRequisition;
use App\Notifications\SiteOfficeRequisitionApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class OfficeRequisitionController extends Controller
{

    public function index()
    {
        $data = OfficeRequisition::with(['user', 'items'])->get();
        // dd($data);
        return view('dean.site-requisition.index', compact('data'));
    }

    public function forUser()
    {
        $data = OfficeRequisition::with(['user', 'items'])
            ->where('user_id', Auth::user()->id)
            ->get();
        // dd($data);
        return view('office.requisition.all', compact('data'));
    }

    public function requisitions()
    {
        $supplies = Supplies::where('quantity', '<=', 3)->get();
        // dd($supplies);
        return view('office.requisition.index', compact('supplies'));
    }


    public function show($id)
    {
        $data = OfficeRequisition::with(['items', 'user:id,name,email'])->find($id);
        return view('dean.site-requisition.show', compact('data'));
    }


    public function getRequisitions(Request $request)
    {
        $rules = [
            'source_of_fund' => 'required|string',
            'purpose_project' => 'required|string',
            'item_quantity.*' => 'required|integer|min:1',
            'unit_cost.*' => 'required|numeric',
            'total.*' => 'required|numeric',
            'purchase_order.*' => 'required|string',
            'remarks.*' => 'nullable|string'
        ];
        $messages = [
            'source_of_fund.required' => 'Source of Fund is required.',
            'purpose_project.required' => 'Purpose/Project is required.',
            'item_quantity.*.required' => 'Quantity is required.',
            'item_quantity.*.min' => 'Quantity must be at least 1.',
            'unit_cost.*.required' => 'Unit Cost is required.',
            'total.*.required' => 'Total is required.',
            'purchase_order.*.required' => 'Purchase Order # is required.'
        ];

        $signatureData = $request->input('signature');

        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);

        $signature = base64_decode($signatureData);

        $fileName = 'signature_' . time() . '.png';

        $filePath = public_path('signatures/' . $fileName);

        file_put_contents($filePath, $signature);


        // $data->dean_signature = 'signatures/' . $fileName;

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $officeRequisition = OfficeRequisition::create([
            'user_id' => auth()->id(),
            'source_of_fund' => $request->input('source_of_fund'),
            'purpose_project' => $request->input('purpose_project'),
            'signature' => 'signatures/' . $fileName
        ]);

        $officeRequisitionId = $officeRequisition->id;

        foreach ($request->input('item_name') as $index => $itemName) {
            OfficeRequisitionItems::create([
                'office_requisitions_id' => $officeRequisitionId,
                'item_quantity' => $request->input('item_quantity')[$index],
                'item_name' => $itemName,
                'unit_cost' => $request->input('unit_cost')[$index],
                'total' => $request->input('total')[$index],
                'purchase_order' => $request->input('purchase_order')[$index],
                'remarks' => $request->input('remarks')[$index],
            ]);
        }

        $deans = Role::where('name', 'dean')->first()->users;
        foreach ($deans as $dean) {
            $dean->notify(new NewOfficeRequisition($officeRequisitionId, 'New Site Office Requisition has arrived, please respond immediately.', 'Pending for Approval'));
        }

        return redirect()->back()->with('message', 'Requisition created successfully!');
    }


    public function approve(Request $request)
    {
        $validated = $request->validate([
            'requisition_id' => 'required|integer|exists:office_requisitions,id',
            'signature' => 'required',
        ]);

        $signatureData = $validated['signature'];

        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);

        $signature = base64_decode($signatureData);

        $fileName = 'signature_' . time() . '.png';

        $filePath = public_path('signatures/' . $fileName);

        file_put_contents($filePath, $signature);

        $data = OfficeRequisition::find($validated['requisition_id']);

        $data->dean_signature = 'signatures/' . $fileName;
        $data->status = 'Approved';
        $data->save();


        $user = User::find($data->user_id);

        $user->notify(new SiteOfficeRequisitionApproval("Hi, $user->name, your request for requisition has been approved by Dean"));

        return redirect()->back()->with('message', 'Requisition has been approved!');
    }


    public function print($id)
    {
        $data = OfficeRequisition::with('items')->find($id);
        return view('office.requisition.print', compact('data'));
    }
}
