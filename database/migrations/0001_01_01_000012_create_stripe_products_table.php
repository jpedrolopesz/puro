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
        Schema::create("stripe_products", function (Blueprint $table) {
            $table->id();
            $table->string("stripe_product_id")->unique();
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("order")->default(0);
            $table->integer("column_count")->default(3);
            $table->string("monthly_price_id")->nullable();
            $table->string("yearly_price_id")->nullable();
            $table->integer("monthly_unit_amount")->nullable();
            $table->integer("yearly_unit_amount")->nullable();
            $table->string("monthly_recurring_interval")->nullable();
            $table->string("yearly_recurring_interval")->nullable();
            $table->json("features")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("stripe_products");
    }
};
