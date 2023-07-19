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
            if (!Schema::hasColumn('auctions', 'bestsellers')) {
                $table->enum('bestsellers', ['0', '1'])->default(0);
                $table->enum('new_release', ['0', '1'])->default(0);
                $table->integer('bestsellers_order')->default(0);
                $table->integer('new_release_order')->default(0);
            }
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
            if (Schema::hasColumn('auctions', 'bestsellers')) {
                $table->dropColumn('bestsellers');
                $table->dropColumn('new_release');
                $table->dropColumn('bestsellers_order');
                $table->dropColumn('new_release_order');
            }
        });
    }
};
