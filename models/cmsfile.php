<?php

use \Eloquent;

class CmsFile extends Eloquent {

	public static $table = 'files';

	public static $timestamps = true;

	public function filetexts()
	{
		return $this->has_many('CmsFileText', 'file_id');
	}

	public function pages()
	{
		return $this->has_many_and_belongs_to('CmsPage', 'files_pages');
	}

	public function banners()
	{
		return $this->has_many_and_belongs_to('CmsBanner', 'files_banners');
	}

	public function galleries()
	{
		return $this->has_many_and_belongs_to('CmsGallery', 'files_galleries');
	}

	public function downloads()
	{
		return $this->has_many_and_belongs_to('CmsDownload', 'files_downloads');
	}
	
	//GETTERS

	public function get_updated_date()
	{
		return strftime('%d %b %Y - %H:%M', strtotime($this->get_attribute('updated_at')));
	}

	public function get_created_date()
	{
		return strftime('%d %b %Y - %H:%M', strtotime($this->get_attribute('created_at')));
	}

	//CREATE THUMB ON UPLOAD (in ajax_page.php)
	public static function create_thumb($upload_path, $file_name, $file_ext)
	{

		$path = $upload_path . $file_name;

		$thumb_settings = Config::get('cms::theme.thumb');
		$thumb_options = Config::get('cms::settings.thumb_options');
		$thumb_path = $upload_path . Config::get('cms::settings.thumb_path');

		foreach($thumb_settings as $setting) {

			$thumb = PhpThumbFactory::create(path('public').$path, $thumb_options);

			if($setting['method'] == 'resize')
				$thumb->resize($setting['width'], $setting['height']);
			if($setting['method'] == 'adaptiveResize')
				$thumb->adaptiveResize($setting['width'], $setting['height']);
			if($setting['method'] == 'cropFromCenter')
				$thumb->cropFromCenter($setting['width'], $setting['height']);

			//CREATE SUBDIR IF NOT EXISTS
			if(!file_exists(path('public').$thumb_path)) mkdir(path('public').$thumb_path);

			//CREATE THUMB FILE NAME
			$thumb_name = str_replace('.' . $file_ext, $setting['suffix'] . '.' . $file_ext, $file_name);

			$thumb->save(path('public').$thumb_path . $thumb_name, $file_ext);

		}

		//return true;
		return '/' . $thumb_path . $thumb_name;

	}

}
