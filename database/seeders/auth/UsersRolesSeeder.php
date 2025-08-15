<?php

declare(strict_types=1);

namespace Database\Seeders\auth;

use App\Models\User;
use Illuminate\Database\Seeder;

final class UsersRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['full_name' => 'Pak Ardianto', 'username' => 'administrator', 'role' => 'administrator'],
            ['full_name' => 'Bu Tanti', 'username' => 'production', 'role' => 'production'],
            ['full_name' => 'Bu Sigma Putri', 'username' => 'sales', 'role' => 'sales'],
            ['full_name' => 'Pak Arif', 'username' => 'inventory', 'role' => 'inventory'],
            ['full_name' => 'Testing', 'username' => 'testing', 'role' => 'testing'],
        ];

        foreach ($users as $userData) {
            User::factory()->create([
                'full_name' => $userData['full_name'],
                'username' => $userData['username'],
            ])->assignRole($userData['role']);
        }
    }
}
