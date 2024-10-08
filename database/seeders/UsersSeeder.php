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
        $user = User::factory()->create([
            "name" => "User Tenant Demo",
            "email" => "usertenant@demo.com",
            "password" => bcrypt("password"),
        ]);

        $tenant = Tenant::factory()->create([
            "creator_id" => $user->id,
        ]);

        $user->update(["tenant_id" => $tenant->id]);

        Domain::factory()->create([
            "tenant_id" => $tenant->id,
            "domain" => "demo",
        ]);
    }
}
