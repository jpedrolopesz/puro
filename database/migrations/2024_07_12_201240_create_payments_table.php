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
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("amount");
            $table->string("currency");
            $table->string("status");
            $table->string("transaction_type")->nullable();
            $table->decimal("amount_refunded", 10, 2)->default(0);
            $table->boolean("refunded")->default(false);
            $table->boolean("disputed")->default(false);
            $table->string("failure_code")->nullable();
            $table->string("failure_message")->nullable();
            $table->boolean("captured")->default(true);
            $table->string("description");
            $table->dateTime("payment_date");
            $table->string("customer_name")->nullable();
            $table->string("customer_email")->nullable();
            $table->string("payment_method_type")->nullable();
            $table->string("payment_method_last4")->nullable();
            $table->string("payment_method_brand")->nullable();
            $table->string("receipt_email")->nullable();
            $table->string("application_fee_amount")->nullable();
            $table->string("capture_method")->nullable();
            $table->timestamps();

            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("set null");
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
