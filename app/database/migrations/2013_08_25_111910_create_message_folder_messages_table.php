<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessageFolderMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('message_folder_messages', function(Blueprint $table) {
			$table->increments('id');
			$table->string('message_id', 10)->index();
			$table->string('folder_id', 10)->index();
			$table->string('user_id', 10)->index();
			$table->timestamps();
			$table->unique(array('message_id', 'folder_id', 'user_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('message_folder_messages');
	}

}
