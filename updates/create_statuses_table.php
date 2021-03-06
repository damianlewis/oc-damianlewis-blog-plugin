<?php

namespace DamianLewis\Blog\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateStatusesTable extends Migration
{

    public function up()
    {
        Schema::create('damianlewis_blog_statuses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('damianlewis_blog_statuses');
    }
}
