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
        if(!Schema::hasColumn('wine_users', 'connection_id')) {
            Schema::table('wine_users', function (Blueprint $table) {
                $table->string('connection_id')->after('stripe_customer_id')->nullable();
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
        if(Schema::hasColumn('wine_users', 'connection_id')) {
            Schema::table('wine_users', function (Blueprint $table) {
                $table->dropColumn('connection_id');
            });
        }
    }
};
