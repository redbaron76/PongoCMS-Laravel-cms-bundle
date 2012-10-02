<?php

class Cms_Create_Blogs_Tags {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE BLOGS_TAGS TABLE
		Schema::create('blogs_tags', function($table) {
			$table->increments('id');
			$table->integer('cmsblog_id');
			$table->integer('cmstag_id');
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
		Schema::drop('blogs_tags');
	}

}