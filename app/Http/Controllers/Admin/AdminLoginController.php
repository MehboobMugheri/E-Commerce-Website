<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\FlareClient\View;

class AdminLoginController extends Controller
{
    public function index(){
        return View('Admin.login');
    }

    public function authenticate(Request $request){
        $validation = Validator::make($request->all(),[
            'email'     => 'required | email',
            'password'  => 'required | max:8'
        ]);
        if ($validation->passes()) {
            // return view('welcome');
            if (Auth::guard('admin')->attempt([
                'email'     => $request->email,
                'password'  => $request->password]))
                {
                    $admin = Auth::guard('admin')->user();
                    if ($admin->role == 0) {
                        return redirect()->route('admin.home');
                    }else {
                        Auth::guard('admin')->logout();
                        return redirect()->route('admin.login')->with('error','You can not access Admin Panel');
                    }
                    // return redirect()->route('admin.home');
                    }else {
                        return redirect()->route('admin.login')->with('error','please enter valid email and password');
                    }
        }else {
            return redirect()->route('admin.login')
                    ->withErrors($validation)
                    ->withInput($request->only('email'));
        }
    }
}
