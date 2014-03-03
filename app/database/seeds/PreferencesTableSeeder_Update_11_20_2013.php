<?php

class PreferencesTableSeeder_Update_11_20_2013 extends Seeder {

	public function run()
	{
		$preferences = array(
			array(
				'name'        => 'Popover interaction',
				'keyName'     => 'POPOVER_TYPE',
				'value'       => 'click|hover|focus',
				'default'     => 'click',
				'display'     => 'select',
				'description' => 'How you would like to interact with popover text.',
			),
		);

		// Uncomment the below to run the seeder
		DB::table('preferences')->insert($preferences);
	}

}
