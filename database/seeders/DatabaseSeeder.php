
<?php
use Illuminate\Database\Seeder;
use Database\Seeders\{
    UsersSeeder,
    AdminSeeder,
    ConversationSeeder,
    MessageSeeder
};

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        //$this->call(AdminSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ConversationSeeder::class);
        //$this->call(MessageSeeder::class);

        //$this->call(MailSeeder::class);
        //$this->call(TenantsSeeder::class);
        //$this->call(TenantSeeder::class);
    }
}

