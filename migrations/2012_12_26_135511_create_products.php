<?php

class Cms_Create_Products {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE PRODUCTS TABLE
		Schema::create('products', function($table) {
			$table->increments('id');
			$table->string('slug', 255);
			$table->string('name', 255);
			$table->string('brand', 255);
			$table->string('zone', 5);
			$table->integer('order_id')->defaults(Config::get('cms::settings.order'));
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
		Schema::drop('products');
	}

}