<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\Fluid;
use App\Models\LaboratoryEquipment;
use Illuminate\Http\Request;

class DFluidController extends Controller
{
    public function index()
    {
        $fluids = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Hydraulics and Fluids');
            })->get();

        return view('dean.fluid.index', compact('fluids'));
    }
}
