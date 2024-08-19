<?php

namespace Database\Seeders;

use App\Models\Mail;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mails = Mail::factory(10)->create();

        $mails->each(function ($mail) {
            Message::factory(3)->create([
                "mail_id" => $mail->id,
            ]);
        });
    }
}
