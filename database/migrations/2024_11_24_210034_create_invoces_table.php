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
        Schema::create('invoces', function (Blueprint $table) {
            $table->increments('inv_id');
            $table->dateTime('inv_date_begin')->nullable();
            $table->float('inv_totalAmount');
            $table->boolean('inv_finish')->default(0);
            $table->dateTime('inv_date_finish')->nullable();
            $table->unsignedInteger('inv_tableId');
            $table->foreign('inv_tableId')->references('tab_id')->on('tables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoces');
    }
};
