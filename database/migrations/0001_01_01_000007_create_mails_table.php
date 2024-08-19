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
            $table
                ->foreignId("sender_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table
                ->foreignId("receiver_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->string("name");
            $table->string("email");
            $table->string("subject");
            $table->text("text");
            $table->boolean("read")->default(false);
            $table->json("labels");
            $table->timestamp("date")->nullable();
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
