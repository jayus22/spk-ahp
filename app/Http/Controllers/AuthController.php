<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

 class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('test');
    }
}
