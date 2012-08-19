<?php

class Cms_Create_Files_Galleries {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE FILES_GALLERIES TABLE
		Schema::create('files_galleries', function($table) {
			$table->increments('id');
			$table->integer('cmsfile_id');
			$table->integer('cmsgallery_id');
			$table->integer('order_id')->defaults(Config::get('cms::settings.order'));
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
		Schema::drop('files_galleries');
	}

}