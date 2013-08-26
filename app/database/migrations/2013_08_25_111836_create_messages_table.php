<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->string('uniqueId', 10);
			$table->primary('uniqueId');
			$table->integer('message_type_id')->index();
			$table->string('sender_id', 10)->index();
			$table->string('receiver_id', 10)->index();
			$table->string('parent_id', 10)->index()->nullable();
			$table->string('child_id', 10)->index()->nullable();
			$table->string('title');
			$table->text('content');
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
		Schema::drop('messages');
	}

}
