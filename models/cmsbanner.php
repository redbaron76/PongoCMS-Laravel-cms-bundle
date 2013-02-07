<?php

use \Eloquent;

class CmsBanner extends Eloquent {

	public static $table = 'banners';

	public static $timestamps = true;

	public function files()
	{
		return $this->has_many_and_belongs_to('CmsFile', 'files_banners')
		->with(array('url', 'date_off', 'is_blank', 'wm', 'order_id'))
		->order_by('files_banners.order_id', 'asc');
	}


	//GETTERS

	public function get_date_off()
	{
		return strftime('%d/%m/%Y', strtotime($this->get_attribute('date_off')));
	}


}