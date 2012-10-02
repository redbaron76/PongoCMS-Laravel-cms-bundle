<?php

class Cms_Create_Galleries {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE GALLERY TABLE
		Schema::create('galleries', function($table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('thumb', 50);
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
		Schema::drop('galleries');
	}

}