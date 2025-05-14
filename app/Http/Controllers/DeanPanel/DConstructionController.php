<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\Construction;
use App\Models\LaboratoryEquipment;
use Illuminate\Http\Request;

class DConstructionController extends Controller
{
    public function index()
    {
        $constructions = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'General Construction');
            })->get();

        return view('dean.construction.index', compact('constructions'));
    }
}
