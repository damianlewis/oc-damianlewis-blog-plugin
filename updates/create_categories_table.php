<?php

namespace DamianLewis\Blog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('damianlewis_blog_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('damianlewis_blog_categories');
    }
}
