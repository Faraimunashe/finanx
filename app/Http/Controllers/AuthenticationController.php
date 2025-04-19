<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasRole('treasurer')){
            return redirect()->route('organizations.index');
        }else{
            return "get up and work";
        }
    }
}
