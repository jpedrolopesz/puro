
<?php
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Admin;
use Database\Seeders\TenantSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(TenantSeeder::class);
    }
}

