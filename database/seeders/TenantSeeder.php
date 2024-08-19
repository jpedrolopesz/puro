<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Domain;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::factory()->create();

        DB::table("admin_password_reset_tokens")->insert([
            "email" => $admin->email,
            "token" => Str::random(40),
            "created_at" => now(),
        ]);

        // Adicionar usuário (criador)
        $creator = User::updateOrCreate(
            ["email" => "admin@example.com"],
            [
                "name" => "Joao Zamonelo",
                "password" => bcrypt("password"),
                "tenant_id" => 1,
            ]
        );

        // Adicionar tenant
        $tenancy = Tenant::create([
            "tenancy_name" => "Apple",
            "status" => "active",
            "email" => "tenant@example.com",
            "tenancy_db_name" => "tenant_" . Str::uuid(),
            "tenancy_about" =>
                'You can\'t compress the program without quantifying the open-source SSD pixel!',
            "creator_id" => $creator->id,
        ]);

        $creator
            ->newSubscription("default", "price_1LSRHkGQW0U1PfqxJO7EGsHx")
            ->create("pm_card_visa");

        $latestSubscription = $creator
            ->subscriptions()
            ->latest("created_at")
            ->first();

        Domain::factory(1)->create(["tenant_id" => $tenancy->id]);

        createUser(
            $tenancy,
            $tenancy->name,
            $tenancy->email,
            \App\Enums\UserRole::SuperAdmin
        );
        createUser($tenancy, null, null, \App\Enums\UserRole::Admin);
    }
}

// Função auxiliar para criar usuários
function createUser(
    $tenant,
    $name = null,
    $email = null,
    $role = \App\Enums\UserRole::Admin
) {
    $faker = Faker::create();

    $user = User::factory()->create([
        "tenant_id" => $tenant->id,
        "name" => $name ?? $faker->name,
        "email" => $email ?? $faker->email,
        "role" => $role,
    ]);

    // Inserindo registros nas tabelas relacionadas
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

    return $user;
}
