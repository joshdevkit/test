<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function index()
    {
        return view('dashboard');
    }
}
