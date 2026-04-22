<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['title' => 'admin'],
            ['title' => 'coach'],
            ['title' => 'trainee'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['title' => $role['title']],
                $role
            );
        }
    }
}
