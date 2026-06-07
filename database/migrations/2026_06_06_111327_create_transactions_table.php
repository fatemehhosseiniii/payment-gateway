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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pay_request_id')->constrained('pay_requests')->cascadeOnUpdate()->restrictOnDelete();
            $table->float('amount');

            $table->string('trac_code')->unique();

            $table->string('refid')->nullable();
            $table->string('transaction_id')->nullable()->unique();
            $table->timestamp('pay_date')->nullable();

            $table->string('pay_status')->nullable();

            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
