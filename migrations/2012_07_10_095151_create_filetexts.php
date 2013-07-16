<?php

class Cms_Create_Filetexts {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE FILETEXTS TABLE
		Schema::create('filetexts', function($table) {
			$table->increments('id');
			$table->integer('file_id');
			$table->text('alt');
			$table->text('title');
			$table->text('caption');
			$table->string('label', 255);
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
		Schema::drop('filetexts');
	}

}
