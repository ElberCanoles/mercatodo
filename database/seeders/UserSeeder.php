<?php

namespace Database\Seeders;

use App\Enums\User\RoleType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # register default admin
        User::create([
            'name' => 'Merca Todo',
            'last_name' => 'Tienda Online',
            'email' => 'admin@mercatodo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->assignRole(RoleType::Administrator);

        # register some users buyers demo
        User::factory()->count(500)->create()->each(function ($user) {
            $user->assignRole(RoleType::Buyer);
        });
    }
}
