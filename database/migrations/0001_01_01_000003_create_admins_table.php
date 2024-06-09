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
        Schema::create("admins", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table
                ->enum("role", ["super_admin", "moderator"])
                ->default("super_admin");

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create("admin_password_reset_tokens", function (
            Blueprint $table
        ) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("admins");
        Schema::dropIfExists("admin_password_reset_tokens");
    }
};
