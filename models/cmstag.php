<?php

use \Eloquent;

class CmsTag extends Eloquent {

	public static $table = 'tags';

	public static $timestamps = true;

	public function blogs()
	{
		return $this->has_many_and_belongs_to('CmsBlog', 'blogs_tags')
		->order_by('tags.name', 'asc');
	}

}