<?php

use \Eloquent;

class CmsGallery extends Eloquent {

	public static $table = 'galleries';

	public static $timestamps = true;

	public function files()
	{
		return $this->has_many_and_belongs_to('CmsFile', 'files_galleries')
		->order_by('files_galleries.order_id', 'asc');
	}

	//THUMB TYPE DROPDOWN
	public static function select_thumb()
	{
			
		$arr = Config::get('cms::theme.thumb');

		$thumbs = array();		

        foreach ($arr as $thumb => $value) {

            $thumbs[$thumb] = $thumb;            

        }

		return $thumbs;

	}






}