<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::create([
            'name' => 'khaled',
            'email' => 'admin@adminable.com',
            'status' => 'offline',
            'password' => bcrypt('eRROR404@'),
        ]);
        $superadmin->assignRole('super admin');
    
        $admin = User::create([
            'name' => 'ahmed', 
            'email' => 'ahmed@gmail.com',
            'status' => 'offline',
            'password' => bcrypt('eRROR404@'),
        ]);
    
        $admin->assignRole('admin');
 
    }
}
