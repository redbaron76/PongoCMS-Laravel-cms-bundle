<?php

class Cms_Create_Menus_Pages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE USERS TABLE
		Schema::create('menus_pages', function($table) {
			$table->increments('id');
			$table->integer('cmsmenu_id');
			$table->integer('cmspage_id');
			$table->integer('order_id')->defaults(Config::get('cms::settings.order'));
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
		Schema::drop('menus_pages');
	}

}