<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class RegisterController extends Controller
{
    public function reg()
    {
        return view('register/register');
    }
}
