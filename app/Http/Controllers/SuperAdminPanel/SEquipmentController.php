<?php

namespace App\Http\Controllers\SuperAdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class SEquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();

        return view('superadmin.equipment.index', compact('equipments'));
    }
}
