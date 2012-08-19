<?php

class Cms_Create_Elements {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE ELEMENTS TABLE
		Schema::create('elements', function($table) {
			$table->increments('id');
			$table->integer('author_id');
			$table->string('name', 255);
			$table->string('label', 255);
			$table->text('text');
			$table->string('zone', 20);
			$table->string('lang', 5);
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
		Schema::drop('elements');
	}

}