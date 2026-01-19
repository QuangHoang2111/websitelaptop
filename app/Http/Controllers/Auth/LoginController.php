<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
  

    use AuthenticatesUsers;

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($user->utype === 'ADM') {
            return redirect('/admin');
        } else {
            return redirect('/user');
        }
    }   

    public function logout(\Illuminate\Http\Request $request)
    {
        $this -> guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/home');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
