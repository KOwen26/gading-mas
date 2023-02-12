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
        //
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->foreignId('company_id');
            $table->string('product_name');
            $table->integer('product_quantity')->default(0);
            $table->integer('product_buy_price')->default(0);
            $table->integer('product_sell_price')->default(0);
            $table->string('product_description')->nullable();
            $table->string('product_status');
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
        //
        Schema::dropIfExists('products');
    }
};