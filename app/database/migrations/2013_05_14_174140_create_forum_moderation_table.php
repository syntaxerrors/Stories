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
            $table->string('resource_type')->index();
            $table->string('resource_id', 11)->index();
            $table->string('user_id', 10)->index();
            $table->text('reason');
            $table->boolean('adminReviewFlag')->index();
            $table->boolean('completeFlag')->index();
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
        Schema::drop('forum_moderation');
    }

}
