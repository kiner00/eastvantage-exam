<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            $user = User::create([
                'full_name' => $role->name . ' User',
                'email' => strtolower($role->name) . '@example.com',
            ]);

            $user->roles()->attach($role->id);
        }
    }
}
