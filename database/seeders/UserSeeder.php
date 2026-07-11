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
        $pengurusIntiRole   = Role::firstOrCreate(['name' => 'pengurus_inti']);
        $pengurusHarianRole = Role::firstOrCreate(['name' => 'pengurus_harian']);

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@pipmarsi.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $pengurusInti = User::firstOrCreate(
            ['email' => 'pengurus.inti@pipmarsi.id'],
            [
                'name' => 'Pengurus Inti',
                'password' => Hash::make('password'),
            ]
        );
        $pengurusInti->assignRole($pengurusIntiRole);

        $pengurusHarian = User::firstOrCreate(
            ['email' => 'pengurus.harian@pipmarsi.id'],
            [
                'name' => 'Pengurus Harian',
                'password' => Hash::make('password'),
            ]
        );
        $pengurusHarian->assignRole($pengurusHarianRole);
    }
}
