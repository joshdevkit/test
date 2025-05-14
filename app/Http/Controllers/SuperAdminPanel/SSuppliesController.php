<?php

namespace App\Http\Controllers\SuperAdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Supplies;
use Illuminate\Http\Request;

class SSuppliesController extends Controller
{
    public function index()
    {
        $supplies = Supplies::all();

        return view('superadmin.supplies.index', compact('supplies'));
    }
}
