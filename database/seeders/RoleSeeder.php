<?php

namespace Database\Seeders;

use App\Domain\Users\Enums\RoleType;
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
