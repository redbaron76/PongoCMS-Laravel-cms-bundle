<?php

class Cms_Create_Blogs_Blogs {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE PAGES_PAGES TABLE
		Schema::create('blogs_blogs', function($table) {
			$table->increments('id');
			$table->integer('cmsblog_id');
			$table->integer('cmsblogrel_id');
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
		Schema::drop('blogs_blogs');
	}

}