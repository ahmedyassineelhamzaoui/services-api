<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


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
            ]);
        }
        Auth::user()->update(['status' => 'online']);
        return response()->json([
            'success' => 'you are log in',
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function logout(Request $request)
    {
        Auth::user()->update(['status' => 'offline']);
        Auth::logout();
        return response()->json([
            'success' => 'Successfully logged out',
        ]);
    }
   
    public function createUser(Request $request)
    {
        $request->validate
        ([
            'first_name'     => 'required|string|max:20',
            'last_name'     => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('email', $request->email);
                })
            ],
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'     => $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->assignRole('admin');
        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'user has been created successfuly',
            'user'    => $user,
            'authorization' => [
                'token' => $token,
                'type'  => 'bearer'
            ]
        ]);
    }
}
