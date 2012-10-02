<?php

class Cms_Create_Translations {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE TRANSLATIONS TABLE
		Schema::create('translations', function($table) {
			$table->increments('id');
			$table->string('lang_from', 5);
			$table->text('word');
			$table->text('value');
			$table->string('lang_to', 5);
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
		Schema::drop('translations');
	}

}