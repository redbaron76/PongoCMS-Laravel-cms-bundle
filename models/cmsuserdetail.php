<?php

use \Eloquent;

class CmsUserDetail extends Eloquent {

	public static $table = 'users_details';

	public static $timestamps = false;

	public function user()
	{
		return $this->has_one('CmsUser', 'id');
	}

	

}