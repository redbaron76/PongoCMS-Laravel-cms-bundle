<?php

class Cms_Create_Products_Translations {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE PRODUCTS TRANSLATIONS
		Schema::create('products_translations', function($table) {
			$table->increments('id');
			$table->integer('product_id');
			$table->string('slug', 255);
			$table->string('title', 255);
			$table->text('keyw')->nullable();
			$table->text('descr')->nullable();
			$table->text('preview');
			$table->text('text');
			$table->string('lang', 5);
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
		Schema::drop('products_translations');
	}

}