<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumModerationlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_moderation_logs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('forum_moderation_id')->index();
			$table->string('user_id', 10)->index();
			$table->string('action');
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
		Schema::drop('forum_moderation_logs');
	}

}
