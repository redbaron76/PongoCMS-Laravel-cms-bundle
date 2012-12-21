<?php

use \Eloquent;

class CmsBlog extends Eloquent {

	public static $table = 'blogs';

	public static $timestamps = true;

	public function role()
	{
		return $this->belongs_to('CmsRole', 'role_id');
	}

	public function user()
	{
		return $this->belongs_to('CmsUser', 'author_id');
	}

	public function pages()
	{
		return $this->has_many_and_belongs_to('CmsPage', 'blogs_pages')
		->order_by('is_default', 'desc')
		->order_by('blogs_pages.created_at', 'asc');
	}

	public function blogrels()
	{
		return $this->has_many_and_belongs_to('CmsBlog', 'blogs_blogs', 'cmsblogrel_id', 'cmsblog_id')
		->order_by('name', 'asc');
	}

	public function tags()
	{
		return $this->has_many_and_belongs_to('CmsTag', 'blogs_tags')
		->order_by('tags.name', 'asc');
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

	public function get_dmy()
	{
		return strftime('%d/%m/%Y', strtotime($this->get_attribute('datetime_on')));
	}

	public function get_datetime_blog()
	{
		return strftime('%A %d %B %H:%M', strtotime($this->get_attribute('datetime_on')));
	}

	public function get_date_blog()
	{
		return strftime('%A %d %B', strtotime($this->get_attribute('datetime_on')));
	}

	public function get_datetime_on()
	{
		return date(GET_DATETIME(), strtotime($this->get_attribute('datetime_on')));
	}

	public function get_datetime_off()
	{
		return date(GET_DATETIME(), strtotime($this->get_attribute('datetime_off')));
	}

	public function get_dt_on()
	{
		return strftime('%d %b %Y - %H:%M', strtotime($this->get_attribute('datetime_on')));
	}

	public function get_dt_off()
	{
		return strftime('%d %b %Y - %H:%M', strtotime($this->get_attribute('datetime_off')));
	}

}
