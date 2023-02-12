<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('transaction_id')->primary();
            $table->foreignId('user_id');
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('company_id');
            $table->decimal('transaction_total', $precision = 10, $scale = 2)->default(0);
            $table->decimal('transaction_discount', $precision = 10, $scale = 2)->default(0);
            $table->decimal('transaction_tax', $precision = 10, $scale = 2)->default(0);
            $table->decimal('transaction_grand_total', $precision = 10, $scale = 2)->default(0);
            $table->dateTime('transaction_date', $precision = 0)->useCurrent();
            $table->dateTime('transaction_due_date', $precision = 0)->useCurrent();
            $table->string('transaction_notes')->nullable();
            $table->string('payment_status');
            $table->string('transaction_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};