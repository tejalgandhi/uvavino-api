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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wine_user_id')->constrained();
                $table->string('wine_name');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->foreignId('drink_type_id')->constrained();
                $table->string('bottle_size')->nullable();
                $table->foreignId('country_id')->constrained();
                $table->foreignId('region_id')->constrained();
                $table->foreignId('producer_id')->constrained();
                $table->string('wine_maker')->nullable();
                $table->foreignId('type_of_wine')->constrained('wine_types');
                $table->integer('year')->nullable();
                $table->string('grape_varieties')->nullable();
                $table->foreignId('wine_tag_id')->constrained();
                $table->text('storage_condition')->nullable();
                $table->text('review_and_rewards')->nullable();
                $table->string('medal')->nullable();
                $table->string('award_name')->nullable();
                $table->integer('score')->nullable();
                $table->string('review_name')->nullable();
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
        Schema::dropIfExists('products');
    }
};
