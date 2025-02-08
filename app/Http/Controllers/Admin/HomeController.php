<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function Index(){
        return view('Admin.dashboard');
        echo "Welcome" . Auth::user()->name ."<a href='". route('admin.logout') ."'>Logout</a>";
        
        // return redirect()->route('admin.login');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login'); 
    }
}
