<?php

namespace App\Http\Controllers\OfficePanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendaroController extends Controller
{
    public function index()
    {
        // Logic to handle a request to the calendar page can go here
        return view('office.calendaro');  // Assumes the view is at resources/views/calendar.blade.php
    }
}
