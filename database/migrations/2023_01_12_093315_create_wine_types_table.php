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
        if (!Schema::hasTable('wine_types')) {
            Schema::create('wine_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->integer('status')->default(1);
                $table->integer('is_approved')->default(1);
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
        Schema::dropIfExists('wine_types');
    }
};
