<?php

namespace DamianLewis\Blog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTagsTable extends Migration
{

    public function up()
    {
        Schema::create('damianlewis_blog_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
        });

        Schema::create('damianlewis_blog_post_tag', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->primary(['post_id', 'tag_id'], 'damianlewis_blog_post_tag_primary');

            $table->foreign('post_id')
                ->references('id')
                ->on('damianlewis_blog_posts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('tag_id')
                ->references('id')
                ->on('damianlewis_blog_tags')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('damianlewis_blog_post_tag', function (Blueprint $table) {
            $table->dropForeign('damianlewis_blog_post_tag_post_id_foreign');
            $table->dropForeign('damianlewis_blog_post_tag_tag_id_foreign');
        });

        Schema::dropIfExists('damianlewis_blog_post_tag');
        Schema::dropIfExists('damianlewis_blog_tags');
    }
}
