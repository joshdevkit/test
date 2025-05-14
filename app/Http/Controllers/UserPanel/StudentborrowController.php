<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplies;
use Illuminate\Support\Facades\Auth;

class StudentborrowController extends Controller
{
    public function create()
    {

        return view('profile.studentsborrow.create');
    }
}
