<?php

use \Eloquent;

class CmsDownload extends Eloquent {

	public static $table = 'downloads';

	public static $timestamps = true;

	public function files()
	{
		return $this->has_many_and_belongs_to('CmsFile', 'files_downloads')
		->order_by('files_downloads.order_id', 'asc');
	}


}