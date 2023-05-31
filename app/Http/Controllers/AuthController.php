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
        $userauth = auth()->user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
             return response()->json(['errors' => $validator->errors()]);
        }
        if($userauth){
            if($userauth->hasPermissionTo('user-create')){
                $users = User::all('first_name','last_name','email','status');
                $user = User::create([
                    'first_name'     => $request->first_name,
                    'last_name'     => $request->last_name,
                    'email'    => $request->email,
                    'status'   => 'offline',
                    'password' => Hash::make($request->password)
                ]);
                $user->assignRole('admin');
                return response()->json([
                    'success' => 'user has been created successfuly',
                    'user'  => $user
                ]);
            }
            return response()->json([
                'permissions' => 'you don\'t have permessions see users'
            ]); 
        }
        return response()->json([
            'error' => 'somthing went wrong',
        ]);

    }
    public function deleteUser(Request $request)
    {
        $authUser = auth()->user();
        if($authUser){
            if($authUser->hasPermissionTo('user-delete')){
                $user = User::find($request->id);
                if($user){
                    $user->delete();
                    return response()->json([
                        'success' => 'user deleted successfuly'
                    ]);
                }
                return response()->json([
                    'error' => 'not found'
                ]);
            }
            return response()->json([
                'permessions' => 'permessions not allowd'
            ]);
        }
        return response()->json([
            'error' => 'user not found'
        ]);
    }
    public function updateUser(Request $request)
    {
        $userAuth = auth()->user();
        if($userAuth){
           if($userAuth->hasPermissionTo('user-edit')){
            $user = User::find($request->id);
              if($user){
                 if($request->has('first_name')){
                    $validator = Validator::make($request->all(), [
                        'first_name' => 'required|string|between:2,100',
                    ]);
                    if($validator->fails()){
                        return response()->json(['errors' => $validator->errors()]);
                    }
                     $user->first_name = $request->first_name;
                 }
                 if($request->has('last_name')){
                    $validator = Validator::make($request->all(), [
                        'last_name' => 'required|string|between:2,100',
                    ]);
                    if($validator->fails()){
                        return response()->json(['errors' => $validator->errors()]);
                    }
                    $user->last_name = $request->last_name;
                 }
                 if($request->has('email')){
                    $validator = Validator::make($request->all(), [
                        'email' => 'required|string|email|max:100',
                    ]);
                    if($validator->fails()){
                        return response()->json(['errors' => $validator->errors()]);
                    }
                    $user_email = User::where('email',$request->email)->first()  ;

                    if($user_email){
                        if($user_email->email == $user->email){
                            $user->email = $request->email;
                        }else{
                            return response()->json(['exist' => 'this email already exist']); 
                        }
                    }
                    $user->email = $request->email;
                 }
                 $user->save();
                 return response()->json([
                    'success' => 'user has been updated successfuly'
                 ]);
              }
              return response()->json([
                'error' => 'User not found'
              ]);
           }
           return response()->json([
            'permissions' => 'permission not allowd'
         ]);
        }
        return response()->json([
           'error' => 'page not found'
        ]);

    }
        
}
