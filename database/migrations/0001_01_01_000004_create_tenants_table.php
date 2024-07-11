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

            //$table->string("stripe_id")->nullable()->index();
            //$table->timestamp("trial_ends_at")->nullable();

            $table->timestamps();
            $table->json("data")->nullable();
        });

        Schema::create("activity_logs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenancy_id")->constrained("tenants");
            $table->string("activity");
            $table->timestamp("date");
            $table->timestamps();
        });

        Schema::create("resource_usages", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenancy_id")->constrained("tenants");
            $table->string("storage");
            $table->integer("users");
            $table->timestamps();
        });

        Schema::create("support_tickets", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenancy_id")->constrained("tenants");
            $table->string("subject");
            $table->string("status");
            $table->timestamp("created_at")->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists("support_tickets");
        Schema::dropIfExists("resource_usages");
        Schema::dropIfExists("activity_logs");
        Schema::dropIfExists("tenants");
    }
}
