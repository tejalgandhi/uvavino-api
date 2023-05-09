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
        if (!Schema::hasTable('auctions')) {
            Schema::create('auctions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->longText('description')->nullable();
                $table->integer('is_private')->default(1);
                $table->integer('type')->default(1);
                $table->integer('max_price')->default(0);
                $table->string('invitations')->nullable();
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
        Schema::dropIfExists('auctions');
    }
};
