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
        Schema::create("messages", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->unsignedBigInteger("conversation_id");
            $table->unsignedBigInteger("sender_id");
            $table->string("sender_type");
            $table->text("content");
            $table->boolean("read")->default(false);

            $table->timestamp("date")->nullable();

            $table
                ->foreign("conversation_id")
                ->references("id")
                ->on("conversations")
                ->onDelete("cascade");
            $table->index(["sender_id", "sender_type"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("messages");
    }
};
