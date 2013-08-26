<?php

class Message_typesTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('message_types')->delete();

		$message_types = array(
			array(
				'name'       => 'Standard',
				'keyName'    => 'standard',
			),
			array(
				'name'       => 'Experience',
				'keyName'    => 'experience',
			),
			array(
				'name'       => 'Moderation Alert',
				'keyName'    => 'moderation-alert',
			),
			array(
				'name'       => 'Action Approved',
				'keyName'    => 'action-approved',
			),
			array(
				'name'       => 'Character Approved',
				'keyName'    => 'character-approved',
			),
		);

		// Uncomment the below to run the seeder
		DB::table('message_types')->insert($message_types);
	}

}