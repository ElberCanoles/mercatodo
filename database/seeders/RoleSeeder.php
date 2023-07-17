<?php

namespace Database\Seeders;

use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => Roles::ADMINISTRATOR]);
        Role::create(['name' => Roles::BUYER]);
    }
}
