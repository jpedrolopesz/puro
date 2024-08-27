<?php

namespace Database\Seeders;

use App\Models\{Conversation, Message};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Conversation::all()->each(function ($conversation) {
            Message::factory()
                ->count(5)
                ->create([
                    "conversation_id" => $conversation->id,
                ]);
        });
    }
}
