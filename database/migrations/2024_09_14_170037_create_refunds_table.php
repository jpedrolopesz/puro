<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create("refunds", function (Blueprint $table) {
            $table->id();
            $table->foreignId("payment_id")->constrained()->onDelete("cascade");
            $table->string("stripe_refund_id")->unique();
            $table->decimal("amount", 10, 2);
            $table->string("status");
            $table->string("reason")->nullable();
            $table->dateTime("refund_date");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("refunds");
    }
};
