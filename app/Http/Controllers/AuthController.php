<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;

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
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
             return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'     => $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->assignRole('admin');
        if($user){
            if($user->hasPermissionTo('create-user')){
                $users = User::all('first_name','last_name','email','status');
                return response()->json([
                    'success' => 'user has been created successfuly',
                    'users'  => $users
                ],200);
            }
            return response()->json([
                'permissions' => 'you don\'t have permessions see users'
            ],403); 
        }
        return response()->json([
            'error' => 'somthing went wrong',
        ],404);

    }
}
