<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAdminReviewFlagToForumModerationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forum_moderation', function(Blueprint $table) {
			$table->boolean('adminReviewFlag')->index();
			$table->boolean('completeFlag')->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('forum_moderation', function(Blueprint $table) {
			$table->dropColumn('adminReviewFlag');
			$table->dropColumn('completeFlag');
		});
	}

}
