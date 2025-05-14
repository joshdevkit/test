<?php

namespace App\Http\Controllers\SuperAdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ComputerEngineering;
use App\Models\Construction;
use App\Models\Equipment;
use App\Models\Fluid;
use App\Models\LaboratoryEquipment;
use App\Models\OfficeRequest;
use App\Models\Requisition;
use App\Models\Supplies;
use App\Models\Surveying;
use App\Models\Testing;

use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    public function index()
    {
        $totals = [
            'computer' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Computer Engineering');
                })
                ->count('equipment'),
            'construction' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'General Construction');
                })
                ->count('equipment'),
            'surveying' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Surveying');
                })
                ->count('equipment'),
            'testing' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Testing & Mechanics');
                })
                ->count('equipment'),
            'fluid' => LaboratoryEquipment::with(['category', 'items'])
                ->whereHas('category', function ($query) {
                    $query->where('name', 'Hydraulics and Fluids');
                })
                ->count('equipment'),
            'supplies' => Supplies::count(),
            'equipments' => Equipment::count(),
            'office_transaction' => Requisition::count(),
            'laboratory_transaction' => OfficeRequest::count(),
        ];
        return view('superadmin.dashboard', compact('totals'));
    }
}
