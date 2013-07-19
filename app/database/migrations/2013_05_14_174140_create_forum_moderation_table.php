<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumModerationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_moderation', function(Blueprint $table) {
            $table->increments('id');
            $table->string('resource_name')->index();
            $table->integer('resource_id')->index();
            $table->integer('user_id')->index();
            $table->text('reason');
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
        Schema::drop('forum_moderation');
    }

}
