<?php

class Cms_Create_Products_Options {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('products_options', function($table) {
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('amount');
			$table->string('size', 255);
			$table->string('color_name', 255);
			$table->string('color_code', 255);
			$table->string('sku', 255);
			$table->boolean('is_valid');
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
		Schema::drop('products_options');
	}

}