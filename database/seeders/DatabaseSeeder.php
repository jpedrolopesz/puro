<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::factory(1)
            ->create([
                "tenancy_db_name" => "tenant_" . Str::uuid(8),
            ])
            ->each(function ($tenant) {
                // Seed domains for each tenant
                Domain::factory(1)->create(["tenant_id" => $tenant->id]);

                // Seed users for each tenant
                User::factory(1)
                    ->create([
                        "tenant_id" => $tenant->id,
                    ])
                    ->each(function ($user) {
                        DB::table("password_reset_tokens")->insert([
                            "email" => $user->email,
                            "token" => Str::random(40),
                            "created_at" => now(),
                        ]);

                        DB::table("sessions")->insert([
                            "id" => Str::random(40),
                            "user_id" => $user->id,
                            "ip_address" => "192.168.1.1", // Example IP
                            "user_agent" =>
                                "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36",
                            "payload" => Str::random(500),
                            "last_activity" => now()->timestamp,
                        ]);
                    });
            });
    }
}
