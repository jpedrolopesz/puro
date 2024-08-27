<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversation = Conversation::factory()
            ->has(Message::factory()->count(5))
            ->create();
    }
}
