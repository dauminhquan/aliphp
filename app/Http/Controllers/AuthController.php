<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLogin(){
        if(Auth::check())
        {
            return back();
        }
        else{
            return view('login');
        }
    }
    public function postLogin(Request $request){

        $data = $request->except(['_token','remember']);

        if(Auth::attempt($data,$request->has('remember')))
        {
            return response()->redirectToRoute('home');
        }
        else{
            return back();
        }
    }
    public function logout(){
        Auth::logout();
        return response()->redirectToRoute('login');
    }
}
