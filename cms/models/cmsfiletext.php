<?php

use \Eloquent;

class CmsFileText extends Eloquent {

	public static $table = 'filetexts';

	public static $timestamps = true;

	public function file()
	{
		return $this->belongs_to('CmsFile');
	}

}