<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wine_user_id');
            $table->string('company_name')->nullable();
            $table->string('nif')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('post_code')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_default')->default(0);
            $table->string('iso_alpha2',5)->nullable();
            $table->string('dial_code',10)->nullable();

            $table->foreign('wine_user_id')
                ->references('id')
                ->on('wine_users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_address');
    }
}
