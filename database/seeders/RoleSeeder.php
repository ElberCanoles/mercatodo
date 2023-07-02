<?php

namespace Database\Seeders;

use App\Enums\User\RoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => RoleType::ADMINISTRATOR]);
        Role::create(['name' => RoleType::BUYER]);
    }
}
