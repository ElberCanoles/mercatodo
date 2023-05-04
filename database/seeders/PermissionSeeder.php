<?php

namespace Database\Seeders;

use App\Enums\User\RoleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  registering admin role permissions
        $routesAdminCollection = Route::getRoutes();

        foreach ($routesAdminCollection as $key => $value) {
            if (Str::startsWith(haystack: $value->action['as'] ?? '', needles: 'admin.')) {
                try {
                    Permission::create(['name' => $value->action['as']])->assignRole(RoleType::ADMINISTRATOR);
                } catch (\Throwable $throwable) {
                }
            }
        }

        //  registering buyer role permissions
        $routesBuyerCollection = Route::getRoutes();

        foreach ($routesBuyerCollection as $key => $value) {
            if (Str::startsWith(haystack: $value->action['as'] ?? '', needles: 'buyer.')) {
                try {
                    Permission::create(['name' => $value->action['as']])->assignRole(RoleType::BUYER);
                } catch (\Throwable $throwable) {
                }
            }
        }
    }
}
