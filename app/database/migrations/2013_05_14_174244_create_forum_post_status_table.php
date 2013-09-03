<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumPostStatusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_post_status', function(Blueprint $table) {
            $table->increments('id');
            $table->string('forum_post_id', 10)->index();
            $table->integer('forum_support_status_id')->index();
            $table->timestamps();
            $table->unique(array('forum_post_id', 'forum_support_status_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forum_post_status');
    }

}
