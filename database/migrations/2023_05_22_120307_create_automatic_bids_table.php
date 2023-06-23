<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('automatic_bids')) {

            Schema::create('automatic_bids', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wine_user_id')->constrained();
                $table->foreignId('auction_id')->constrained();
                $table->double('max_amount');
                $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('automatic_bids');
    }
};
