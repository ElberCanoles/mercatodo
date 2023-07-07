<?php

namespace Database\Seeders;

use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
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

        User::factory()->create([
            'name' => config(key: 'admin.name'),
            'last_name' => config(key: 'admin.last_name'),
            'email' => config(key: 'admin.email'),
            'password' => Hash::make(config(key: 'admin.password'))
        ])->each(function ($administrator) {
            $administrator->assignRole(role: Roles::ADMINISTRATOR);
            $administrator->permissions()->sync(Permission::all());
        });

        User::factory()->count(count: 1000)->create()->each(function ($user) {
            $user->assignRole(Roles::BUYER);
            $user->givePermissionTo(Permissions::ORDERS_INDEX);
            $user->givePermissionTo(Permissions::ORDERS_SHOW);
        });
    }
}
