<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Initial accounts. Change the admin password immediately after the first
 * login — see the README.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Nick Ricamora', 'email' => 'nick@recursivefrog.ph', 'role' => User::ROLE_ADMIN],
            ['name' => 'Sales', 'email' => 'sales@recursivefrog.ph', 'role' => User::ROLE_EDITOR],
            ['name' => 'Front', 'email' => 'front@recursivefrog.ph', 'role' => User::ROLE_EDITOR],
            ['name' => 'Tech', 'email' => 'tech@recursivefrog.ph', 'role' => User::ROLE_EDITOR],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [...$user, 'password' => Hash::make('password')],
            );
        }
    }
}
