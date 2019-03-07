<?php

namespace DamianLewis\Blog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePostsTable extends Migration
{

    public function up()
    {
        Schema::create('damianlewis_blog_posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('excerpt')->nullable();
            $table->text('body');
            $table->string('featured_image')->nullable();
            $table->string('featured_image_title')->nullable();
            $table->string('featured_image_description')->nullable();
            $table->date('published_at');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('damianlewis_blog_categories')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('status_id')
                ->references('id')
                ->on('damianlewis_blog_statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('damianlewis_blog_posts', function (Blueprint $table) {
            $table->dropForeign('damianlewis_blog_posts_category_id_foreign');
            $table->dropForeign('damianlewis_blog_posts_status_id_foreign');
        });

        Schema::dropIfExists('damianlewis_blog_posts');
    }
}
