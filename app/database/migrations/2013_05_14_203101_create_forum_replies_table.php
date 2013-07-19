<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumRepliesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_replies', function(Blueprint $table) {
            $table->increments('id');
            $table->string('uniqueId')->index();
            $table->integer('forum_post_id')->index();
            $table->integer('forum_reply_type_id')->index();
            $table->integer('user_id')->index();
            $table->integer('character_id')->index();
            $table->string('name');
            $table->string('keyName');
            $table->text('content');
            $table->integer('quote_id')->index()->null();
            $table->string('quote_type')->null();
            $table->boolean('moderatorLockedFlag')->default(0);
            $table->boolean('approvedFlag')->index();
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
        Schema::drop('forum_replies');
    }

}
