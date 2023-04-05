<?php

namespace Database\Seeders;

use App\Enums\User\RoleType;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #  registering system roles
        Role::create(['name' => RoleType::ADMINISTRATOR]);
        Role::create(['name' => RoleType::BUYER]);
    }
}
