<?php

use \Eloquent;

class CmsPage extends Eloquent {

	public static $table = 'pages';

	public static $timestamps = true;

	public function role()
	{
		return $this->belongs_to('CmsRole', 'role_id');
	}

	public function user()
	{
		return $this->belongs_to('CmsUser', 'author_id');
	}

	public function elements()
	{
		return $this->has_many_and_belongs_to('CmsElement', 'elements_pages')
		->order_by('elements_pages.order_id', 'asc')
		->order_by('zone', 'asc');
	}

	public function files()
	{
		return $this->has_many_and_belongs_to('CmsFile', 'files_pages');
	}

	public function menus()
	{
		return $this->has_many_and_belongs_to('CmsMenu', 'menus_pages')
		->order_by('menus_pages.order_id', 'asc');
		
	}

	public function pagerels()
	{
		return $this->has_many_and_belongs_to('CmsPage', 'pages_pages', 'cmspagerel_id', 'cmspage_id')
		->order_by('name', 'asc');
	}

	public function blogs()
	{
		return $this->has_many_and_belongs_to('CmsBlog', 'blogs_pages')
		->order_by('blogs.created_at', 'asc');
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

	public function get_sitemap_update()
	{
		return strftime('%Y-%m-%d', strtotime($this->get_attribute('updated_at')));
	}

	//PAGE SETTINGS POSITION DROPDOWN RECURSIVE
	private static function sub_slug($path_top_name, $path_top_id, $self_id = 0) {
        
        $slugs = array();
        
        $rs = self::where_parent_id($path_top_id)
        				->where('id', '<>', $self_id)
                        ->order_by('order_id','asc')
                        ->order_by('name','asc')
                        ->get(array('id','parent_id','name'));
                        
        foreach ($rs as $path) {
            $slugs[$path->id] = $path_top_name .' > '. $path->name;
            $second = call_user_func_array('self::sub_slug', array($path_top_name .' > '. $path->name, $path->id, $self_id));
            $slugs = ($slugs + $second);
        }
        
        return $slugs;
        
    }

	//PAGE SETTINGS POSITION DROPDOWN
	public static function select_top_slug($lang, $self = 0, $first = true)
	{
		if($first) {
			
			$slugs = array(0 => LL('cms::form.page_topcat', CMSLANG));

		} else {

			$slugs = array();

		}

		$rs = self::where_parent_id(0)
					->where('id', '<>', $self)
                    ->where_lang($lang)
                    ->where_is_home(0)
                    ->where_is_valid(1)
                    ->order_by('is_home', 'desc')
                    ->order_by('order_id', 'asc')
                    ->order_by('name', 'asc')
                    ->get();

        foreach ($rs as $path) {
            
            $slugs[$path->id] = $path->name;
            $second = call_user_func_array('self::sub_slug', array($path->name, $path->id, $self));
            $slugs = ($slugs + $second);
            
        }

		return $slugs;

	}

	//PAGE GENERAL POSITION DROPDOWN
	public static function select_page_slug($lang, $self = 0, $first = true)
	{
		if($first) {
			
			$slugs = array(0 => LL('cms::form.page_select', CMSLANG));

		} else {

			$slugs = array();

		}

		$rs = self::where_parent_id(0)
					->where('id', '<>', $self)
                    ->where_lang($lang)
                    ->where_is_home(0)
                    ->where_is_valid(1)
                    ->order_by('is_home', 'desc')
                    ->order_by('order_id', 'asc')
                    ->order_by('name', 'asc')
                    ->get();

        foreach ($rs as $path) {
            
            $slugs[$path->id] = $path->name;
            $second = call_user_func_array('self::sub_slug', array($path->name, $path->id));
            $slugs = ($slugs + $second);
            
        }

		return $slugs;

	}

	//SELECT EXTRA_ID DROPDOWN
	public static function select_extra_id()
	{
		$extras = Config::get('cms::settings.extra_id');

		$ext = array();

		foreach ($extras as $key => $value) {
			$ext[$key] = ucfirst(LABEL('cms::label.', $value));
		}

		return $ext;
	}

	//SELECT LANG TRANSLATION
	public static function select_lang_translation()
	{
		$langs = Config::get('cms::settings.langs');

		$select = array('', LL('cms::form.page_select', CMSLANG));

		foreach ($langs as $key => $value) {
			if($key != Config::get('cms::settings.language')) $select[$key] = $value;
		}

		return $select;

	}

	//GET PAGE SLUG
	public static function get_page_slug($pid)
	{
		
		$page = self::find($pid);

		return $page->slug;

	}

	public static function update_child_slugs($page_id, $slug)
	{

		if(!empty($page_id)) {

			//GET OLD PAGE
			$old_page = self::find($page_id);
			$old_path = $old_page->slug;
			$path = explode('/', $old_path);
			//GET OLD SLUG
			$old_slug = end($path);

			$pages = self::where_lang($old_page->lang)
							->where('slug', 'LIKE', '%/'.$old_slug.'/%')
							->order_by('order_id', 'asc')
							->get();

			foreach ($pages as $page) {
				
				$item = self::find($page->id);
				$item->slug = str_replace('/'.$old_slug.'/', '/'.$slug.'/', $page->slug);
				$item->save();

			}

			return true;

		}

	}

	public static function update_role_level($role_id, $role_level)
	{
		//GET PAGE
		self::where_role_id($role_id)->update(array('role_level' => $role_level));

	}


	//RECURSIVE SITEMAP
    public static function recursive_sitemap($parent_id)
    {

    	//GET PAGE DATA
		$data = self::with(array('user','elements'))
				->where_parent_id($parent_id)								
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('self::recursive_sitemap', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		return $new_data;

    }

    //RECURSIVE SITE SITEMAP
    public static function recursive_site_sitemap($parent_id)
    {

    	//GET PAGE DATA
		$data = self::with(array('files' => function($query) {
			$query->where_is_image(1)->where_is_valid(1);
		}))
				->where_parent_id($parent_id)								
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('self::recursive_site_sitemap', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		return $new_data;

    }

    //RECURSIVE FILE PAGES
    public static function recursive_filespages($parent_id)
    {

    	//GET PAGE DATA
		$data = self::with(array('files'))
				->where_parent_id($parent_id)								
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('self::recursive_filespages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		return $new_data;

    }

    //RECURSIVE MENU PAGES
    public static function recursive_menuspages($parent_id)
    {

    	//GET PAGE DATA
		$data = self::with(array('menus'))
				->where_parent_id($parent_id)								
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('self::recursive_menuspages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		return $new_data;

    }

    //RECURSIVE PAGES
    public static function recursive_pages($parent_id)
    {

    	//GET PAGE DATA
		$data = self::where_parent_id($parent_id)								
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('self::recursive_pages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		return $new_data;

    }

    


}