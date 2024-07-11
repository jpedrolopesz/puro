<?php

use Illuminate\Database\Seeder;
use App\Models\Tenancy;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\ResourceUsage;
use App\Models\SupportTicket;

class TenancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adicionar usuário (criador)
        $creator = User::firstOrCreate(
            ["email" => "admin@example.com"],
            ["name" => "Joao Zamonelo", "password" => bcrypt("password")] // Ajuste o password conforme necessário
        );

        // Adicionar tenancy
        $tenancy = Tenancy::create([
            "tenancy_name" => "Apple",
            "status" => "active",
            "subscription_level" => "basic",
            "tenancy_db_name" => "tenant_9bf91441-7d3a-4a5a-b01f-532f33822cfb",
            "tenancy_about" =>
                'You can\'t compress the program without quantifying the open-source SSD pixel!',
            "creator_id" => $creator->id,
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
    }
}
