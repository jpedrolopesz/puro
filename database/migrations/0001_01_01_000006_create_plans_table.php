<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("plans", function (Blueprint $table) {
            $table->string("id")->primary(); // ID do produto no Stripe
            $table->string("name"); // Nome do plano
            $table->text("description")->nullable(); // Descrição do plano
            $table->boolean("active"); // Status de atividade
            $table->decimal("unit_amount", 10, 2); // Quantidade unitária
            $table->string("currency", 3); // Moeda
            $table->string("interval"); // Intervalo de cobrança (ex: 'month', 'year')
            $table->string("nickname")->nullable(); // Apelido do preço
            $table->json("metadata")->nullable(); // Metadados adicionais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("plans");
    }
};
