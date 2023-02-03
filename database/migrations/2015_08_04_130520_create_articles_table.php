<?php

use GemaDigital\Framework\app\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::statement('SET SESSION sql_require_primary_key=0');
        } catch (Exception $e) {}

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->string('slug', 127)->unique()->nullable();
            $table->text('content');
            $table->string('image')->nullable();
            $table->text('images')->nullable();
            $table->text('videos')->nullable();
            $table->enum('status', config('constant.general.publish'));
            $table->date('date');
            $table->text('documents')->nullable();
            $table->boolean('featured')->default(0);
            $table->text('link')->nullable();
            $table->text('extras')->nullable();
            $table->text('extras_translatable')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('article_related', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained();
            $table->foreignId('related_id')
                ->references('id')
                ->on('articles')
                ->onDelete('cascade');

            $table->primary(['article_id', 'related_id']);
        });

        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained();
            $table->foreignId('tag_id')->constrained();

            $table->primary(['article_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_related');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('articles');
    }
}
