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
        Schema::create("payments", function (Blueprint $table) {
            $table->id()->unique();
            $table->string("stripe_payment_id")->unique();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("amount");
            $table->string("currency");
            $table->string("status");
            $table->string("description");
            $table->dateTime("payment_date");
            $table->string("customer_name")->nullable();
            $table->string("customer_email")->nullable();
            $table->string("payment_method_type")->nullable(); // Tipo de método de pagamento (sensível)
            $table->string("payment_method_last4")->nullable(); // Últimos 4 dígitos do método de pagamento (sensível)
            $table->string("payment_method_brand")->nullable(); // Marca do método de pagamento (sensível)
            $table->string("receipt_email")->nullable();
            $table->string("application_fee_amount")->nullable();
            $table->string("capture_method")->nullable();
            // Adicione outros campos conforme necessário
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("payments");
    }
};
