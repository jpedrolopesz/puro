<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\ResourceUsage;
use App\Models\SupportTicket;
use App\Models\Domain;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adicionar administrador
        $admin = Admin::factory()->create();

        DB::table("admin_password_reset_tokens")->insert([
            "email" => $admin->email,
            "token" => Str::random(40),
            "created_at" => now(),
        ]);

        // Adicionar usuário (criador)
        $creator = User::firstOrCreate(
            ["email" => "admin@example.com"],
            ["name" => "Joao Zamonelo", "password" => bcrypt("password")] // Ajuste o password conforme necessário
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
        // Criação de assinatura para o usuário criador
        // Criação de assinatura para o usuário criador
        $creator
            ->newSubscription("default", "price_1PbVo2GQW0U1PfqxgSgO9fgK")
            ->create("pm_card_visa");

        // Obter a última assinatura criada pelo usuário criador
        $latestSubscription = $creator
            ->subscriptions()
            ->latest("created_at")
            ->first();

        // Criar um novo item de assinatura associado à última assinatura criada
        SubscriptionItem::create([
            "subscription_id" => $latestSubscription->id,
            "stripe_id" => "price_1PbVo2GQW0U1PfqxgSgO9fgK", // Substitua pelo ID real do item no Stripe, se aplicável
            "stripe_product" => "Product A",
            "quantity" => 1,
        ]);

        // Adicionar activity logs
        $activityLogs = [
            [
                "tenancy_id" => $tenancy->id,
                "activity" => "Status changed to in progress",
                "date" => "2024-06-24 12:00:00",
            ],
            [
                "tenancy_id" => $tenancy->id,
                "activity" => "Tenant created",
                "date" => "2024-06-23 12:00:00",
            ],
        ];

        foreach ($activityLogs as $log) {
            ActivityLog::create($log);
        }

        // Adicionar resource usage
        ResourceUsage::create([
            "tenancy_id" => $tenancy->id,
            "storage" => "50GB",
            "users" => 100,
        ]);

        // Adicionar support tickets
        $supportTickets = [
            [
                "tenancy_id" => $tenancy->id,
                "subject" => "Issue with logging in",
                "status" => "open",
                "created_at" => "2024-06-24 15:00:00",
            ],
        ];

        foreach ($supportTickets as $ticket) {
            SupportTicket::create($ticket);
        }

        // Adicionar domínio
        Domain::factory(1)->create(["tenant_id" => $tenancy->id]);

        // Criar usuários para o tenant
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
