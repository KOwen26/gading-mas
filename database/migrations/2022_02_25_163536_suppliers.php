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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('city_id')->foreignId()->nullable();
            $table->string('supplier_name');
            $table->string('supplier_phone')->nullable();
            $table->string('supplier_email')->nullable();
            $table->string('supplier_contact_name')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_notes')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
};