<?php

class Cms_Create_Blogs_Pages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE BLOG_PAGES TABLE
		Schema::create('blogs_pages', function($table) {
			$table->increments('id');
			$table->integer('cmsblog_id');
			$table->integer('cmspage_id');
			$table->boolean('is_default');
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
		Schema::drop('blogs_pages');
	}

}