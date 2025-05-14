<?php
namespace App\Http\Controllers\LaboratoryPanel;
use App\Http\Controllers\Controller;
use App\Models\Event;

class CalendarController extends Controller
{
    public function index()
    {
        // Logic to handle a request to the calendar page can go here
        return view('laboratory.calendar'); 

       
    }
}
