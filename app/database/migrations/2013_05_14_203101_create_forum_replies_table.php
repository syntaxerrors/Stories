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
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('forum_post_id', 10)->index();
            $table->integer('forum_reply_type_id')->index();
            $table->string('user_id', 10)->index();
            $table->string('character_id', 10)->index()->nullable();
            $table->string('name');
            $table->string('keyName');
            $table->text('content');
            $table->string('quote_id', 10)->index()->nullable();
            $table->string('quote_type')->nullable();
            $table->boolean('moderatorLockedFlag')->default(0);
            $table->boolean('adminReviewFlag')->default(0);
            $table->boolean('approvedFlag')->index();
            $table->timestamps();
            $table->softDeletes();
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
