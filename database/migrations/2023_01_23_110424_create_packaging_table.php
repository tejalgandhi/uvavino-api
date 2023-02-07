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
        if (!Schema::hasTable('packaging')) {
            Schema::create('packaging', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained();
                $table->integer('stock')->nullable();
                $table->integer('price')->nullable();
                $table->integer('weight')->nullable();
                $table->integer('dimension')->nullable();
                $table->integer('quantity')->nullable();
                $table->string('unit')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packaging');
    }
};
