<?php

class Cms_Create_Tags {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE TAGS TABLE
		Schema::create('tags', function($table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->string('lang', 5);
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tags');
	}

}