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
        Schema::create("conversations", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->unsignedBigInteger("initiator_id");
            $table->string("initiator_type");
            $table->unsignedBigInteger("recipient_id");
            $table->string("recipient_type");
            $table->string("subject");
            $table->json("labels")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("conversations");
    }
};
