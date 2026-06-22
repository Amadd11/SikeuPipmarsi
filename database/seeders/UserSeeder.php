<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $bendaharaRole  = Role::firstOrCreate(['name' => 'bendahara']);

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@pipmarsi.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $bendahara = User::firstOrCreate(
            ['email' => 'bendahara@pipmarsi.id'],
            [
                'name' => 'Bendahara',
                'password' => Hash::make('password'),
            ]
        );
        $bendahara->assignRole($bendaharaRole);
    }
}
