<?php

namespace Database\Seeders;

use App\Models\Mail;
use Illuminate\Database\Seeder;

class MailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mail::factory(3)->create();
    }
}
