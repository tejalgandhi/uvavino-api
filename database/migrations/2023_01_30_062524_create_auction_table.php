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
                $table->enum('lot_type', ['1', '2'])->comment('1=>single,2=>multi')->default(1);
                $table->string('title');
                $table->longText('description')->nullable();
                $table->enum('is_private', ['0', '1'])->comment('0=>No,1=>Yes')->default(1);
                $table->enum('type', ['0', '1'])->comment('0=>live,1=>timebased')->default(1);
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
