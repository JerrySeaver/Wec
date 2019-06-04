<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function Login()
    {
    	return view('/Login/Login');
    }
    public function prolist()
    {
    	return view('/index/prolist');
    }
    
}
