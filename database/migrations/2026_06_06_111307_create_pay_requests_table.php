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
        Schema::create('pay_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')->unique()->index();
            $table->foreignId('gateway_id')->constrained('gateways')->cascadeOnUpdate()->restrictOnDelete();
            $table->float('amount');
            $table->bigInteger('order_code')->nullable();
            $table->float('remaining_amount')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('status_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_requests');
    }
};
