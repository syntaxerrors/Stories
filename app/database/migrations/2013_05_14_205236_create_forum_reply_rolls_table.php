<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumReplyRollsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_reply_rolls', function(Blueprint $table) {
            $table->increments('id');
            $table->string('forum_reply_id', 10)->index();
            $table->integer('die');
            $table->integer('roll');
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
        Schema::drop('forum_reply_rolls');
    }

}
