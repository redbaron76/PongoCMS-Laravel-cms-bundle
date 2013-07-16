<?php

class Cms_Create_Roles {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE ROLES TABLE
		Schema::create('roles', function($table) {			
			$table->increments('id');
			$table->string('name', 50);
			$table->integer('level');
		});

		//POPULATE ROLES TABLE
		$roles = Config::get('cms::settings.roles');
		foreach ($roles as $key => $value) {
			$role = new CmsRole();
			$role->name = $key;
			$role->level = $value;
			$role->save();
		}

	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
	}

}