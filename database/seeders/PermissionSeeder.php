<?php

namespace Database\Seeders;

use App\Enums\User\RoleType;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #  registering admin role permissions
        $routesAdminCollection = Route::getRoutes();

        foreach ($routesAdminCollection as $key => $value) {
            if (Str::startsWith($value->action['as'] ?? '', 'admin.')) {
                try {
                    Permission::create(['name' => $value->action['as']])->assignRole(RoleType::Administrator);
                } catch (\Throwable $throwable) {
                }
            }
        }

        #  registering buyer role permissions
        $routesBuyerCollection = Route::getRoutes();

        foreach ($routesBuyerCollection as $key => $value) {
            if (Str::startsWith($value->action['as'] ?? '', 'buyer.')) {
                try {
                    Permission::create(['name' => $value->action['as']])->assignRole(RoleType::Buyer);
                } catch (\Throwable $throwable) {
                }
            }
        }
    }
}
