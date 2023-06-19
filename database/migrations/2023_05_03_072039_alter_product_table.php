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
            $table->foreignId('basket_id')->after('id')->constrained();
            $table->string('title')->after('wine_user_id')->nullable();
            $table->text('description')->after('title')->nullable()->change();
            $table->integer('weight')->after('review_name')->nullable();
            $table->string('dimensions')->after('weight')->nullable();
            $table->integer('basket_quantity')->after('dimensions')->nullable();
            $table->integer('unit_quantity')->after('basket_quantity')->nullable();
            $table->string('unit')->after('unit_quantity')->nullable();
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
            $table->dropForeign(['basket_id']);
            $table->dropColumn('basket_id');
            $table->dropColumn(['title','weight','dimensions','basket_quantity','unit_quantity','unit']);
        });
    }
};
