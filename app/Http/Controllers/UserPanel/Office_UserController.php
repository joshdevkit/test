<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use App\Models\Supplies;
use App\Models\Transactionoffice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\TransactionOfficeRequest;
class Office_UserController extends Controller
{
    public function index()
    {
        $supplies = Supplies::all();

        return view('profile.office_user.index', compact('supplies'));
    }

 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('profile.office_user.create');
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
            'user_name' => 'required',
            'item' => 'required',
            'purpose' => 'required',
            'date_borrowed' => 'required|date',  // Ensure this field is a valid date
        ]);
    
        $office_user = new Transactionoffice();
        $office_user->user_name = $request->user_name;
        $office_user->item = $request->item;
        $office_user->purpose = $request->purpose;
        $office_user->date_borrowed = Carbon::parse($request->date_borrowed);  // Parse to Carbon instance
        $office_user->status = 'pending';  // Default, as specified in the model
    
        $office_user->save();
    
        return redirect()->route('transactionoffice.index')->with('success', 'Transaction saved successfully');
    }
    
    public function show(Transactionoffice $id)
    {
        return view('transactionoffice.index');
    }

}