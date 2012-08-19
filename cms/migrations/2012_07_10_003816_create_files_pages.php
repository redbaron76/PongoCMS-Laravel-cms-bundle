<?php

class Cms_Create_Files_Pages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE USERS TABLE
		Schema::create('files_pages', function($table) {
			$table->increments('id');
			$table->integer('cmsfile_id');
			$table->integer('cmspage_id');
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
		Schema::drop('files_pages');
	}

}