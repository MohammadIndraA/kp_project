<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.index');
    }

    public function login(Request $request)
    {
        {
            $validator = Validator::make($request->all(), [
    
                'email' => 'required|email',
    
                'password' => 'required'
    
            ]);
            if ($validator->fails()){
    
                return response()->json([
    
                        "status" => false,
    
                        "errors" => $validator->errors()
    
                    ]);
    
            } else {
    
                if (Auth::attempt($request->only(["email", "password"]))) {
    
                    return response()->json([
    
                        "status" => true, 
    
                        "redirect" => url("/dashboard")
    
                    ]);
    
                } else {
    
                    return response()->json([
    
                        "status" => false,
    
                        "errors" => ["Invalid credentials"]
    
                    ]);
    
                }
    
            }
    
        }
    }
    
    public function logout()
    {
       Auth::logout();
       request()->session()->invalidate();
       request()->session()->regenerateToken();
        return redirect('/dashboard');
    }
}
