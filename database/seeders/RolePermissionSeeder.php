<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-create',
            'user-edit',
            'user-delete',
            'user-list',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'categorie-list',
            'categorie-create',
            'categorie-edit',
            'categorie-delete',
            'marque-list',
            'marque-create',
            'marque-edit',
            'marque-delete',       
        ];
       
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

      

        $superadmin = 'super admin';
        $admin = 'admin';

        Role::create(['name' => $superadmin])->givePermissionTo(Permission::all());

        Role::create(['name' => $admin])->givePermissionTo([
            array_slice($permissions, 8, 15)
        ]);
        
    }
}
