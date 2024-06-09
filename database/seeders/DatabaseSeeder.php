
<?php
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = Admin::factory()->create();

        DB::table("admin_password_reset_tokens")->insert([
            "email" => $admin->email,
            "token" => Str::random(40),
            "created_at" => now(),
        ]);

        Tenant::factory(1)
            ->create([
                "tenancy_db_name" => "tenant_" . Str::uuid(),
            ])
            ->each(function ($tenant) {
                // Seed domains for each tenant
                Domain::factory(1)->create(["tenant_id" => $tenant->id]);

                createUser(
                    $tenant,
                    $tenant->name,
                    $tenant->email,
                    UserRole::SuperAdmin
                );
                createUser($tenant, null, null, UserRole::Admin);
            });
    }
}

// Função auxiliar para criar usuários
function createUser(
    $tenant,
    $name = null,
    $email = null,
    $role = UserRole::Admin
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

