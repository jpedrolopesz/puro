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
            $table->id();
            $table->unsignedBigInteger("admin_id");
            $table->unsignedBigInteger("user_id");
            $table->string("subject");
            $table->json("labels")->nullable();

            $table->timestamps();

            $table
                ->foreign("admin_id")
                ->references("id")
                ->on("admins")
                ->onDelete("cascade");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->unique(["admin_id", "user_id"]);
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
