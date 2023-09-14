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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('grape_varieties', 'wine_variety');
            $table->dropForeign(['wine_tag_id']);
            $table->dropColumn(['wine_tag_id']);
            $table->string('wine_tag')->nullable()->after('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('wine_variety','grape_varieties');
            $table->dropColumn('wine_tag');
            $table->foreignId('wine_tag_id')->nullable()->constrained();
        });
    }
};
