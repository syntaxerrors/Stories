<?php

class PreferencesTableSeeder_Update_12_17_2013 extends Seeder {

	public function run()
	{
		$preferences = array(
			array(
				'name'        => 'Site Menu',
				'keyName'     => 'SITE_MENU',
				'value'       => 'twitter|utopian',
				'default'     => 'utopian',
				'display'     => 'select',
				'description' => 'Determines the menu bar at the top of the page.',
			),
		);

		// Uncomment the below to run the seeder
		DB::table('preferences')->insert($preferences);
	}

}
