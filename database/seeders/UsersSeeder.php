<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numTenants = 3;
        $usersPerTenant = 3;

        $specificName = "User Tenant Demo";
        $specificEmail = "usertenant@demo.com";
        $specificPassword = bcrypt("password");

        $specificDomain = "demo";

        $tenants = Tenant::factory()->count($numTenants)->create();

        foreach ($tenants as $tenantIndex => $tenant) {
            if ($tenantIndex === 0) {
                User::factory()->create([
                    "name" => $specificName,
                    "tenant_id" => $tenant->id,
                    "email" => $specificEmail,
                    "password" => $specificPassword,
                ]);
            }
            User::factory()
                ->count($usersPerTenant - 1)
                ->create(["tenant_id" => $tenant->id]);

            if ($tenantIndex === 0) {
                Domain::factory()->create([
                    "tenant_id" => $tenant->id,
                    "domain" => $specificDomain,
                ]);
            } else {
                Domain::factory()->create(["tenant_id" => $tenant->id]);
            }
        }
    }
}
