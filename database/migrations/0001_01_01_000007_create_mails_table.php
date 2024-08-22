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
        Schema::create("mails", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->string("email");
            $table->unsignedBigInteger("sender_id");
            $table->string("sender_type");
            $table->unsignedBigInteger("receiver_id");
            $table->string("receiver_type");
            $table->string("subject");
            $table->text("text");
            $table->boolean("read")->default(false);
            $table->json("labels")->nullable();

            $table->timestamp("date")->nullable();

            $table->index(["sender_id", "sender_type"]);
            $table->index(["receiver_id", "receiver_type"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("mails");
    }
};
