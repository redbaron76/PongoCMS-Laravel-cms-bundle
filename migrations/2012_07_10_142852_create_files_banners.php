<?php

class Cms_Create_Files_Banners {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		//CREATE FILES_BANNERS TABLE
		Schema::create('files_banners', function($table) {
			$table->increments('id');
			$table->integer('cmsfile_id');
			$table->integer('cmsbanner_id');
			$table->string('url', 255);
			$table->date('date_off');
			$table->boolean('is_blank');
			$table->boolean('wm');
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
		Schema::drop('files_banners');
	}

}