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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wine_user_id');
            $table->string('wine_name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('drink_type_id');
            $table->string('bottle_size')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('producer_id');
            $table->string('wine_maker')->nullable();
            $table->unsignedBigInteger('type_of_wine');
            $table->integer('year')->nullable();
            $table->string('grape_varieties')->nullable();
            $table->unsignedBigInteger('wine_tag_id');
            $table->text('storage_condition')->nullable();
            $table->text('review_and_rewards')->nullable();
            $table->string('medal')->nullable();
            $table->string('award_name')->nullable();
            $table->integer('score')->nullable();
            $table->string('review_name')->nullable();
            $table->timestamps();
            $table->foreign('wine_user_id')->references('id')->on('wine_users')->onDelete('cascade');
            $table->foreign('drink_type_id')->references('id')->on('drink_types')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('country')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('producer_id')->references('id')->on('producers')->onDelete('cascade');
            $table->foreign('wine_tag_id')->references('id')->on('wine_tags')->onDelete('cascade');
            $table->foreign('type_of_wine')->references('id')->on('wine_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
