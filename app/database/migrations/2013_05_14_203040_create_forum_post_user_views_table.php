<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumPostUserViewsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_user_view_posts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 10)->index();
            $table->string('forum_post_id', 10)->index();
            $table->timestamps();
            $table->unique(array('forum_post_id', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forum_user_view_posts');
    }

}
