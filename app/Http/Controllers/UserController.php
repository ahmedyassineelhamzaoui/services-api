<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function getAllUsers()
    {
        $user = auth()->user();
        $users = User::all('id','first_name','last_name','email','status');
        if($user){
            if(!$user->hasPermissionTo('user-list')){
                return response()->json([
                   'error' => 'you don\'t have permessions see users'
                ]);
            }
            return response()->json([
                'users' => $users
            ]);
        }
        return response()->json([
            'info' => 'something went wrong'
        ]);
    }
}
