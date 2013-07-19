<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('uniqueId')->index();
            $table->integer('forum_category_type_id')->index();
            $table->string('name');
            $table->string('keyName')->index();
            $table->text('description');
            $table->integer('position')->nullable()->index();
            $table->integer('game_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forum_categories');
    }

}
