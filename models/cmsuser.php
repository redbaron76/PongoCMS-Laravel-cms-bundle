<?php

use \Eloquent;

class CmsUser extends Eloquent {

	public static $table = 'users';

	public static $timestamps = true;

	public function role()
	{
		return $this->belongs_to('CmsRole', 'role_id');
	}

	public function pages()
	{
		return $this->has_many('CmsPage', 'author_id');
	}

	public function elements()
	{
		return $this->has_many('CmsElements', 'author_id');
	}

	public function blogs()
	{
		return $this->has_many('CmsBlog', 'author_id');
	}

	public function details()
	{
		return $this->has_one('CmsUserDetail', 'user_id');
	}


	

}
