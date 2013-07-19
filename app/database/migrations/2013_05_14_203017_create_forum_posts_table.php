<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumPostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_posts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('uniqueId')->index();
            $table->integer('forum_board_id')->index();
            $table->integer('forum_post_type_id')->index();
            $table->integer('user_id')->index();
            $table->integer('character_id')->index();
            $table->string('name');
            $table->string('keyName')->index();
            $table->text('content');
            $table->integer('views');
            $table->boolean('moderatorLockedFlag');
            $table->boolean('approvedFlag')->index();
            $table->boolean('frontPageFlag')->index();
            $table->timestamps();
            $table->datetime('modified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forum_posts');
    }

}
