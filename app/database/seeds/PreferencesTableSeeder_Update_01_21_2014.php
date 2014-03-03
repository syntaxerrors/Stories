<?php

class PreferencesTableSeeder_Update_01_21_2014 extends Seeder {

	public function run()
	{
		$preferences = array(
			array(
				'name'        => 'Chat Timestamps',
				'keyName'     => 'CHAT_TIMESTAMPS',
				'value'       => 'on|off',
				'default'     => 'on',
				'display'     => 'select',
				'description' => 'Set whether to see chat timestamps.',
			),
		);

		// Uncomment the below to run the seeder
		DB::table('preferences')->insert($preferences);
	}

}
