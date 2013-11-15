<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UsersTableSeeder');
		$this->call('RolesTableSeeder');
		$this->call('ActionsTableSeeder');
		$this->call('ActionRolesTableSeeder');
		$this->call('RoleUsersTableSeeder');
		$this->call('Forum_board_typesTableSeeder');
		$this->call('Forum_category_typesTableSeeder');
		$this->call('Forum_post_typesTableSeeder');
		$this->call('Forum_reply_typesTableSeeder');
		$this->call('Forum_support_statusTableSeeder');
		$this->call('Message_typesTableSeeder');
		$this->call('MessageFoldersTableSeeder');
		$this->call('PreferencesTableSeeder');
		$this->call('Preferences_usersTableSeeder');
	}

}