<?php

namespace Database\Seeders;

use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [];

        $now = now();

        foreach (Permissions::toArray() as $permission) {
            $permissions[] = [
                'name' => $permission,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Permission::insertOrIgnore($permissions);
    }
}
