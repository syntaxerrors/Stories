<?php

class PreferencesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('preferences')->truncate();

		$preferences = array(
			array(
				'name'        => 'Avatar to display',
				'keyName'     => 'AVATAR',
				'value'       => 'avatar|gravatar|none',
				'default'     => 'gravatar',
				'display'     => 'select',
				'description' => 'Select what to display for yourself.  You can chose between an uploaded avatar, your gravatar image or nothing.',
			),
			array(
				'name'        => 'Show your email address to others',
				'keyName'     => 'SHOW_EMAIL',
				'value'       => 'yes|no',
				'default'     => 'yes',
				'display'     => 'select',
				'description' => 'This will allow other users to see your email address.',
			),
		);

		// Uncomment the below to run the seeder
		DB::table('preferences')->insert($preferences);
	}

}
