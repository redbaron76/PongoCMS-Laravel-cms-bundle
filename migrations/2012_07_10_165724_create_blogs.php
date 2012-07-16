<?php

class Cms_Create_Blogs {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE BLOGS TABLE
		Schema::create('blogs', function($table) {
			$table->increments('id');
			$table->integer('role_id');
			$table->integer('role_level');
			$table->integer('author_id');
			$table->string('slug', 255);
			$table->string('name', 255);
			$table->date('datetime_on');
			$table->date('datetime_off');
			$table->text('preview');
			$table->text('text');
			$table->string('title', 255);
			$table->text('keyw')->nullable();
			$table->text('descr')->nullable();
			$table->string('zone', 5);
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
		Schema::drop('blogs');
	}

}
