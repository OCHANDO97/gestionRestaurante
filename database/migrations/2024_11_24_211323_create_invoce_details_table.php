<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoce_details', function (Blueprint $table) {
            $table->increments('invDet_id'); 
            $table->unsignedInteger('invDet_invoceId'); 
            $table->unsignedInteger('invDet_proId');   
            $table->foreign('invDet_invoceId')->references('inv_id')->on('invoces')->onDelete('cascade');
            $table->foreign('invDet_proId')->references('pro_id')->on('products')->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoce_details');
    }
};
