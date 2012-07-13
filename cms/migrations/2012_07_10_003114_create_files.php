<?php

class Cms_Create_Files {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE USERS TABLE
		Schema::create('files', function($table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('ext', 10);
			$table->integer('size');
			$table->integer('w');
			$table->integer('h');
			$table->string('path', 100);
			$table->string('thumb', 100);
			$table->boolean('is_image');
			$table->boolean('is_valid');
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
		Schema::drop('files');
	}

}