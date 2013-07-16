<?php

class Cms_Create_Users_Details {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE USERS DETAILS TABLE
		Schema::create('users_details', function($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('name', 255);
			$table->string('surname', 255);
			$table->string('address', 255);
			$table->text('info');
			$table->string('number', 20);
			$table->string('city', 255);
			$table->string('zip', 20);
			$table->string('state', 20);
			$table->string('country', 255);
			$table->string('tel', 100);
			$table->string('cel', 100);
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_details');
	}

}