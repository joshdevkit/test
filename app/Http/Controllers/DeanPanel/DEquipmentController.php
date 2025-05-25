<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class DEquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::with('items')->get();
        // dd($equipments);
        return view('dean.equipment.index', compact('equipments'));
    }
}
