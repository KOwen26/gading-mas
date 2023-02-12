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
        Schema::create('procurement_details', function (Blueprint $table) {
            $table->id('procurement_detail_id');
            $table->string('procurement_id')->foreignId();
            $table->foreignId('product_id');
            $table->string('product_name');
            $table->integer('product_quantity')->default(0);
            $table->decimal('product_price', $precision = 10, $scale = 2)->default(0);
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
        Schema::dropIfExists('procurement_details');
    }
};