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
        Schema::table('wishlist', function (Blueprint $table) {
            $table->enum('type', ['1', '2','3'])->after('wine_user_id')->comment('1=>auction_package,2=>auction,3=>product')->default(3);
            if (\DB::getDriverName() !== 'sqlite') {

                $table->dropForeign(['basket_id']);
                $table->dropColumn('basket_id');
            }
            $table->bigInteger('ref_id')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('ref_id');
            $table->foreignId('basket_id')->constrained();

        });
    }
};
