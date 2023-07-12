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
        Schema::dropIfExists('wishlist');
        if (!Schema::hasTable('wishlist')) {
            Schema::create('wishlist', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wine_user_id')->constrained();
                $table->enum('type', ['1', '2','3'])->after('wine_user_id')->comment('1=>auction_package,2=>auction,3=>product')->default(3);
                $table->bigInteger('ref_id')->after('type');
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
        Schema::dropIfExists('wishlist');
    }
};
