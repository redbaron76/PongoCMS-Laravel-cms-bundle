<?php

class Cms_Create_Pages_Pages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE PAGES_PAGES TABLE
		Schema::create('pages_pages', function($table) {
			$table->increments('id');
			$table->integer('cmspage_id');
			$table->integer('cmspagerel_id');
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
		Schema::drop('pages_pages');
	}

}