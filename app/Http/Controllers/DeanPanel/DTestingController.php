<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\LaboratoryEquipment;
use App\Models\Testing;
use Illuminate\Http\Request;

class DTestingController extends Controller
{
    public function index()
    {
        $testings = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Testing & Mechanics');
            })->get();

        return view('dean.testing.index', compact('testings'));
    }
}
