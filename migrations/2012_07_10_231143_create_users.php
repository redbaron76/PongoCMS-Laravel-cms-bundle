<?php

class Cms_Create_Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE USERS TABLE
		Schema::create('users', function($table) {
			$table->increments('id');
			$table->integer('role_id');
			$table->string('username', 20);
			$table->string('email', 100);
			$table->string('password', 64);
			$table->integer('role_level');
			$table->string('lang', 5);
			$table->string('editor', 20);
			$table->boolean('is_valid');
			$table->timestamps();
		});

		//GET ADMIN ROLE
        $admin = CmsRole::where_level(Config::get('cms::settings.roles.admin'))->first();

		//POPULATE ADMIN USER
		$user = CmsUser::create(array(
			'role_id' => $admin->id,
			'username' => Config::get('cms::settings.admin_setup.login'),
			'email' => Config::get('cms::settings.admin_setup.login'),
			'password' => Hash::make(Config::get('cms::settings.admin_setup.password')),
			'role_level' => Config::get('cms::settings.roles.admin'),
			'lang' => Config::get('application.language'),
			'editor' => 'ckeditor',
			'is_valid' => 1
		));

	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
