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
        Schema::table('auctions', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_bid')->after('max_price')->nullable();
            $table->foreign('winner_bid')->references('id')->on('auction_bids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropForeign(['winner_bid']);
            $table->dropColumn(['winner_bid']);
        });
    }
};
