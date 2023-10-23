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
        Schema::table('wine_users', function (Blueprint $table) {
            $table->string('image')->nullable()->after('country');
            $table->longText('about_us')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wine_users', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('about_us');
        });
    }
};
