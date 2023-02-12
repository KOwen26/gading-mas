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
        Schema::create('procurements', function (Blueprint $table) {
            $table->string('procurement_id')->primary();
            $table->foreignId('user_id');
            $table->foreignId('supplier_id');
            $table->foreignId('company_id');
            $table->decimal('procurement_total', $precision = 10, $scale = 2)->default(0);
            $table->decimal('procurement_discount', $precision = 10, $scale = 2)->default(0);
            $table->decimal('procurement_tax', $precision = 10, $scale = 2)->default(0);
            $table->decimal('procurement_grand_total', $precision = 10, $scale = 2)->default(0);
            $table->dateTime('procurement_date', $precision = 0)->useCurrent();
            $table->dateTime('procurement_due_date', $precision = 0)->useCurrent();
            $table->string('procurement_notes')->nullable();
            $table->string('payment_status');
            $table->string('procurement_status');
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
        Schema::dropIfExists('procurements');
    }
};