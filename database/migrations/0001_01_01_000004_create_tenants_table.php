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
            $table->string("email")->unique();

            //$table->string("stripe_id")->nullable()->index();
            //$table->string("pm_type")->nullable();
            //$table->string("pm_last_four", 4)->nullable();
            //$table->timestamp("trial_ends_at")->nullable();
            // your custom columns may go here

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
