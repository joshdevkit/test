<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\Supplies;
use Illuminate\Http\Request;

class DSuppliesController extends Controller
{
    public function index()
    {
        $supplies = Supplies::with('items')->get();

        return view('dean.supplies.index', compact('supplies'));
    }
}
