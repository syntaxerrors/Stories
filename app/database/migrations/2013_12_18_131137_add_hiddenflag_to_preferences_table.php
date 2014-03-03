<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddHiddenflagToPreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('preferences', function(Blueprint $table) {
			$table->boolean('hiddenFlag')->default(0)->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('preferences', function(Blueprint $table) {
			$table->dropColumn('hiddenFlag');
		});
	}

}
