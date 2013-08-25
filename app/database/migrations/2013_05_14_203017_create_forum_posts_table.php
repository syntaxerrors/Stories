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
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('forum_board_id', 10)->index();
            $table->integer('forum_post_type_id')->index();
            $table->string('user_id', 10)->index();
            $table->string('character_id', 10)->index()->nullable();
            $table->string('name');
            $table->string('keyName')->index();
            $table->text('content');
            $table->integer('views');
            $table->boolean('moderatorLockedFlag')->default(0);
            $table->boolean('adminReviewFlag')->default(0);
            $table->boolean('approvedFlag')->index();
            $table->boolean('frontPageFlag')->index();
            $table->timestamps();
            $table->datetime('modified_at');
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
        Schema::drop('forum_posts');
    }

}
