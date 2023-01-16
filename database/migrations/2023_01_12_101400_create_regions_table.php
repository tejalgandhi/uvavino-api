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
        if (!Schema::hasTable('regions')) {
            Schema::create('regions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('country_id');
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->enum('status', ['0', '1'])->comment('0=>Inactive,1=>active')->default(1);
                $table->enum('is_approved', ['0', '1'])->comment('0=>No,1=>Yes')->default(1);
                $table->timestamps();
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('regions');
    }
};
