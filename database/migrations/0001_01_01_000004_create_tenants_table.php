<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create("tenants", function (Blueprint $table) {
            $table->id();
            $table->string("tenancy_name");
            $table->string("email")->unique();
            $table->string("status");
            $table->string("tenancy_db_name");
            $table->text("tenancy_about")->nullable();
            $table->foreignId("creator_id")->constrained("users");

            $table->timestamps();
            $table->json("data")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists("tenants");
    }
}
