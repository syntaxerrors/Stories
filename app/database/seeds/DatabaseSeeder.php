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
		$this->call('PreferencesTableSeeder_Update_01_09_2014');
		$this->call('PreferencesTableSeeder_Update_01_21_2014');
		$this->call('PreferencesTableSeeder_Update_11_18_2013');
		$this->call('PreferencesTableSeeder_Update_11_20_2013');
		$this->call('PreferencesTableSeeder_Update_12_17_2013');
	}

}