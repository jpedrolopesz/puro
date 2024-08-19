<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numTenants = 3;
        $usersPerTenant = 3;

        $tenants = Tenant::factory()->count($numTenants)->create();

        foreach ($tenants as $tenant) {
            User::factory()
                ->count($usersPerTenant)
                ->create(["tenant_id" => $tenant->id]);

            Domain::factory()->forTenant($tenant)->create();
        }
    }
}
