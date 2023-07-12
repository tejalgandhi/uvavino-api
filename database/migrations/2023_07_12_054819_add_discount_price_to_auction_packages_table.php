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
        Schema::table('auction_packages', function (Blueprint $table) {
            $table->double('discount_amount', 8, 2)->after('amount')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_packages', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};
