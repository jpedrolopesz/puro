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
            $table->id();
            $table->string("stripe_payment_id")->unique();
            $table->decimal("amount", 10, 2);
            $table->decimal("amount_refunded", 10, 2)->default(0);
            $table->string("currency", 3);
            $table->boolean("refunded")->default(false);
            $table->boolean("disputed")->default(false);
            $table->boolean("captured")->default(true);
            $table->decimal("converted_amount", 10, 2)->nullable();
            $table->decimal("converted_amount_refunded", 10, 2)->nullable();
            $table->string("converted_currency", 3)->nullable();
            $table->string("decline_reason")->nullable();
            $table->string("description")->nullable();
            $table->decimal("fee", 10, 2)->nullable();
            $table->dateTime("refunded_date")->nullable();
            $table->string("statement_descriptor")->nullable();
            $table->string("status");
            $table->string("seller_message")->nullable();
            $table->decimal("taxes_on_fee", 10, 2)->nullable();
            $table->string("card_id")->nullable();
            $table->string("customer_id")->nullable();
            $table->string("customer_description")->nullable();
            $table->string("customer_email")->nullable();
            $table->string("invoice_id")->nullable();
            $table->string("transfer")->nullable();
            $table->json("additional_info")->nullable();
            $table->dateTime("payment_date");

            $table
                ->foreignId("user_id")
                ->nullable()
                ->constrained("users")
                ->onDelete("set null");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table("payments", function (Blueprint $table) {
            $table->dropColumn([
                "disputed",
                "failure_code",
                "failure_message",
                "captured",
            ]);
        });
    }
};
