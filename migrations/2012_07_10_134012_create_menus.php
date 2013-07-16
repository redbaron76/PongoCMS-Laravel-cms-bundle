<?php

class Cms_Create_Menus {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE MENU TABLE
		Schema::create('menus', function($table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('lang', 5);
			$table->integer('parent_start')->defaults(0);
			$table->boolean('is_nested')->defaults(0);
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
		Schema::drop('menus');
	}

}
