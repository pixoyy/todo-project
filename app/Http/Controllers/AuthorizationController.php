<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
     public function index()
    {
        return view(view: 'authorization.index');
    }
}
