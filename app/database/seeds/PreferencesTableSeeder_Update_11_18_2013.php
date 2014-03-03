<?php

class PreferencesTableSeeder_Update_11_18_2013 extends Seeder {

	public function run()
	{
		$preferences = array(
			array(
				'name'        => 'Alert location',
				'keyName'     => 'ALERT_LOCATION',
				'value'       => 'top-left|top|top-right|bottom-right|bottom|bottom-left',
				'default'     => 'top',
				'display'     => 'select',
				'description' => 'Select where you would like the alert box to appear.',
			),
		);

		// Uncomment the below to run the seeder
		DB::table('preferences')->insert($preferences);
	}

}
