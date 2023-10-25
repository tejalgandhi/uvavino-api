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
        Schema::table('user_address', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->text('address_first_line')->after('country')->nullable();
            $table->text('address_second_line')->after('address_first_line')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_address', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->dropColumn(['address_first_line','address_second_line']);
        });
    }
};
