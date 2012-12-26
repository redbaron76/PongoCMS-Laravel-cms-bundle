<?php

class Cms_Create_Products_Pages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE PRODUCTS_PAGES TABLE
		Schema::create('products_pages', function($table) {
			$table->increments('id');
			$table->integer('cmsproduct_id');
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
		Schema::drop('products_pages');
	}

}