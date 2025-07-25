<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            if (auth()->user()->type == 'ADMIN') {
                return redirect()->intended('admin/dashboard');
            } elseif (auth()->user()->type == 'BRANCH') {
                return redirect()->intended('branch/dashboard');
            } else if (auth()->user()->type == 'WAREHOUSE') {
                return redirect()->intended('warehouse/dashboard');
            }else if (auth()->user()->type == 'FACTORY') {
                return redirect()->intended('factory/dashboard');
            }
        } else {
            return redirect()->back()->with('error', 'These credentials do not match our records.');
        }
    }

}
