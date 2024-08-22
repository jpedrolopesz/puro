
<?php
use Database\Seeders\MessageSender;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\MailSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(MailSeeder::class);
        $this->call(MessageSender::class);

        //$this->call(TenantsSeeder::class);
        // $this->call(TenantSeeder::class);
    }
}

