<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verifica se o Admin com o e-mail fixo já existe
        if (!Admin::where("email", "zamonelo@hotmail.com")->exists()) {
            // Cria apenas um Admin com o e-mail fixo
            Admin::factory()->create([
                "email" => "zamonelo@hotmail.com",
            ]);
        }
    }
}
