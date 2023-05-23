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
            'first_name' => 'khaled',
            'last_name' => 'kamal',
            'email' => 'admin@adminable.com',
            'status' => 'offline',
            'password' => bcrypt('eRROR404@'),
        ]);
        $superadmin->assignRole('super admin');
    
        $admin = User::create([
            'first_name' => 'ahmed', 
            'last_name' => 'yassine', 
            'email' => 'ahmed@gmail.com',
            'status' => 'offline',
            'password' => bcrypt('eRROR404@'),
        ]);
    
        $admin->assignRole('admin');
 
    }
}
