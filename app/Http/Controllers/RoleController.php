<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index()
    {
        $user=auth()->user();
        if(!$user){
            return response()->json([
                'error' => 'user not found'
            ]);
        }
        if($user->hasPermissionTo('role-list')){
            $roles = Role::with('permissions')->get();
            return response()->json([
                'roles' => $roles
            ]);
        }
        return response()->json([
            'permissions' => 'permissions not allowd'
        ]);
        
    } 


}
