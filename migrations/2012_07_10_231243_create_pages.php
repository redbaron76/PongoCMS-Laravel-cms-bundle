<?php

class Cms_Create_Pages {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE USERS TABLE
		Schema::create('pages', function($table) {
			$table->increments('id');
			$table->integer('parent_id');
			$table->integer('role_id');
			$table->integer('role_level');
			$table->integer('author_id');
			$table->string('slug', 255);
			$table->string('name', 255);
			$table->text('preview');
			$table->string('title', 255);
			$table->text('keyw')->nullable();
			$table->text('descr')->nullable();
			$table->string('template', 100);
			$table->string('header', 100);
			$table->string('layout', 100);
			$table->string('footer', 100);
			$table->integer('access_level');
			$table->integer('extra_id');
			$table->integer('order_id')->defaults(Config::get('cms::settings.order'));
			$table->string('lang', 5);
			$table->boolean('is_home');
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
		Schema::drop('pages');
	}

}
