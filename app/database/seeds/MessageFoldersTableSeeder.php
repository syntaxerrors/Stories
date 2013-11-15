<?php

class MessageFoldersTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		// DB::table('messagefolders')->delete();

		$messagefolders = array(
			array(
				'uniqueId' => 'uc2nrLJLlw',
				'user_id'  => 'bmeJBz10K2',
				'name'     => 'Inbox'
			),
			array(
				'uniqueId' => 'acQSvjn8Bh',
				'user_id'  => '2bHAJwWCX2',
				'name'     => 'Inbox'
			),
		);

		// Uncomment the below to run the seeder
		DB::table('message_folders')->insert($messagefolders);
	}

}