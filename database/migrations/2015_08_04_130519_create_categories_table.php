<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedInteger('lft')->nullable();
            $table->unsignedInteger('rgt')->nullable();
            $table->unsignedInteger('depth')->nullable();
            $table->string('name');
            $table->string('slug', 127)->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
