<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumPostEditsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_post_edits', function(Blueprint $table) {
            $table->increments('id');
            $table->string('forum_post_id', 10)->index();
            $table->string('user_id', 10)->index();
            $table->text('reason')->nullable();
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
        Schema::drop('forum_post_edits');
    }

}
