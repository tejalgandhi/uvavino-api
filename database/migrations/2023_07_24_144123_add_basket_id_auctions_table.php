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
        if (!Schema::hasColumn('auctions', 'basket_id')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->foreignId('basket_id')->constrained();

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
        if (Schema::hasColumn('auctions', 'basket_id')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->dropForeign(['basket_id']);
                $table->dropColumn(['basket_id']);
            });
        }
    }
};
