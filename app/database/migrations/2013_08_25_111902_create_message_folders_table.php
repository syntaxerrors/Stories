<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessageFoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('message_folders', function(Blueprint $table) {
			$table->string('uniqueId', 10);
			$table->primary('uniqueId');
			$table->string('user_id', 10)->index();
			$table->string('parent_id', 10)->index()->nullable();
			$table->string('name');
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
		Schema::drop('message_folders');
	}

}
