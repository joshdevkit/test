<?php

namespace App\Http\Controllers\DeanPanel;

use App\Http\Controllers\Controller;
use App\Models\LaboratoryEquipment;
use App\Models\Surveying;
use Illuminate\Http\Request;

class DSurveyingController extends Controller
{
    public function index()
    {
        $surveyings = LaboratoryEquipment::with(['category', 'items'])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Surveying');
            })->get();

        return view('dean.surveying.index', compact('surveyings'));
    }
}
