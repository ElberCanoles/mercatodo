<?php

namespace Database\Seeders;

use App\Domain\Users\Enums\RoleType;
use App\Domain\Users\Models\User;
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
        // register default admin
        User::create([
            'name' => config(key: 'admin.name'),
            'last_name' => config(key: 'admin.last_name'),
            'email' => config(key: 'admin.email'),
            'password' => Hash::make(config(key: 'admin.password')),
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->assignRole(RoleType::ADMINISTRATOR);

        // register some users buyers demo
        User::factory()->count(count: 1000)->create()->each(function ($user) {
            $user->assignRole(RoleType::BUYER);
        });
    }
}
