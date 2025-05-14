<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\ComputerEngineering;
use App\Models\LaboratoryEquipment;
use Illuminate\Http\Request;

class DComputerController extends Controller
{
    public function index()
    {
        $computerEngineering = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Computer Engineering');
            })->get();

        return view('dean.computer_engineering.index', compact('computerEngineering'));
    }
}
