<?php

class PreferencesTableSeeder_Update_01_09_2014 extends Seeder {

	public function run()
	{
		$preferences = array(
			array(
				'name'        => 'Collapse Admin General',
				'keyName'     => 'COLLAPSE_ADMIN_GENERAL',
				'value'       => 'true|false',
				'default'     => 'false',
				'display'     => 'select',
				'description' => '',
				'hiddenFlag'  => 1,
			),
			array(
				'name'        => 'Collapse Admin Permissions',
				'keyName'     => 'COLLAPSE_ADMIN_PERMISSIONS',
				'value'       => 'true|false',
				'default'     => 'false',
				'display'     => 'select',
				'description' => '',
				'hiddenFlag'  => 1,
			),
			array(
				'name'        => 'Collapse Admin Types',
				'keyName'     => 'COLLAPSE_ADMIN_TYPES',
				'value'       => 'true|false',
				'default'     => 'false',
				'display'     => 'select',
				'description' => '',
				'hiddenFlag'  => 1,
			),
		);

		// Uncomment the below to run the seeder
		DB::table('preferences')->insert($preferences);
	}

}
