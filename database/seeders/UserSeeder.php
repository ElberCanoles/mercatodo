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
            'name' => config('admin.name'),
            'last_name' => config('admin.last_name'),
            'email' => config('admin.email'),
            'password' => Hash::make(config('admin.password')),
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->assignRole(RoleType::ADMINISTRATOR);

        # register some users buyers demo
        User::factory()->count(1000)->create()->each(function ($user) {
            $user->assignRole(RoleType::BUYER);
        });
    }
}
