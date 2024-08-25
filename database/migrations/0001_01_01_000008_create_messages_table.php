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
            $table->unsignedBigInteger("sender_id");
            $table->string("sender_type");
            $table->unsignedBigInteger("receiver_id");
            $table->string("receiver_type");
            $table->text("text");
            $table->timestamp("date")->nullable();

            $table
                ->foreign("mail_id")
                ->references("id")
                ->on("mails")
                ->onDelete("cascade");
            $table->index(["sender_id", "sender_type"]);
            $table->index(["receiver_id", "receiver_type"]);
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
