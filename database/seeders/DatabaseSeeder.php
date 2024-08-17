
<?php
use Illuminate\Database\Seeder;
use Database\Seeders\MailSeeder;
use Database\Seeders\TenantSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(MailSeeder::class);
        // $this->call(TenantSeeder::class);
    }
}

