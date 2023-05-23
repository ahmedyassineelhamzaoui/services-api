<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modeles\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8|string'
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'error' => 'invalid email or password'
            ],401);
        }
        Auth::user()->update(['status' => 'online']);
        return response()->json([
            'success' => 'you are log in',
        ],200);
    }
}
