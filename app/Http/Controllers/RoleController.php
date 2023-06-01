<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Validator;


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
            $permissions = Permission::all('id','name');
            return response()->json([
                'roles' => $roles,
                'permissions' => $permissions
            ]);
        }
        return response()->json([
            'permissions' => 'permissions not allowd'
        ]);
        
    } 

    public function createRole(Request $request)
    {
        $user=auth()->user();
        if($user){    
            if($user->hasPermissionTo('role-create')){
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:roles,name'
                ]);
                if($validator->fails()){
                     return response()->json(['errors' => $validator->errors()]);
                }
                $role = Role::create(['name' => $request->name]);
                $role->syncPermissions($request->permissions);
                return response()->json([
                    'success' => 'role created successfuly',
                    'role'    => $role
                ]);

            }
            return response()->json([
                'permissions' => 'you don\'t have pemession '
            ]);
        }
        return response()->json([
            'error' => 'anuthorise '
        ]);
    }
    public function deleteRole(Request $request)
    {
        $user=auth()->user();
        if($user){
            if($user->hasPermissionTo('role-delete')){
                $role = Role::find($request->id);
                if($role){
                    $role->delete();
                    return response()->json([
                        'success' => 'role deleted successfuly  '
                    ]);
                }
                return response()->json([
                    'error' => 'role not exist'
                ]);
            }
            return response()->json([
                'permissions' => 'permission not alowd'
            ]);
         }
         return response()->json([
            'error' => 'anauthorized'
         ]);
    
    }
}
