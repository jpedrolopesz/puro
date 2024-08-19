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
            $table->uuid("mail_id");
            $table
                ->foreign("mail_id")
                ->references("id")
                ->on("mails")
                ->onDelete("cascade");
            $table->unsignedBigInteger("sender_id");
            $table
                ->foreign("sender_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->text("text");
            $table->timestamp("date")->nullable();
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
