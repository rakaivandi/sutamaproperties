<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 3 role
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'agen']);
        Role::firstOrCreate(['name' => 'pembeli']);

        // User admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@propertiapp.test'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // User agen
        $agen = User::firstOrCreate(
            ['email' => 'agen@propertiapp.test'],
            ['name' => 'Budi Agen', 'password' => bcrypt('password')]
        );
        $agen->assignRole('agen');

        // User pembeli
        $buyer = User::firstOrCreate(
            ['email' => 'pembeli@propertiapp.test'],
            ['name' => 'Sari Pembeli', 'password' => bcrypt('password')]
        );
        $buyer->assignRole('pembeli');
    }
}