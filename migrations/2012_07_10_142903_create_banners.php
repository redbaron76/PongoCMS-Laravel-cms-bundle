<?php

class Cms_Create_Banners {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE BANNERS TABLE
		Schema::create('banners', function($table) {
			$table->increments('id');
			$table->string('name', 50);
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
		Schema::drop('banners');
	}

}