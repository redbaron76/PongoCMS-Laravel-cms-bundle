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
		->order_by('zone', 'asc')
		->order_by('elements_pages.order_id', 'asc');		
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

	//PREVIEW MARKER

	public function blogs_preview()
	{
		return $this->has_many_and_belongs_to('CmsBlog', 'blogs_pages')
		->where('blogs.datetime_off', '>=', date('Y-m-d H:i:s'))
		// LARAVEL BUG
		// ->group_by('blogs_pages.cmsblog_id')
		->order_by('blogs.datetime_on', 'desc');
	}

	public function blogs_preview_past()
	{
		return $this->has_many_and_belongs_to('CmsBlog', 'blogs_pages')
		->where('blogs.datetime_on', '<', date('Y-m-d H:i:s'))
		->where('blogs.datetime_off', '>=', date('Y-m-d H:i:s'))
		// LARAVEL BUG
		// ->group_by('blogs_pages.cmsblog_id')
		->order_by('blogs.datetime_on', 'desc');
	}

	public function blogs_preview_future()
	{
		return $this->has_many_and_belongs_to('CmsBlog', 'blogs_pages')
		->where('blogs.datetime_on', '>=', date('Y-m-d H:i:s'))
		->where('blogs.datetime_off', '>=', date('Y-m-d H:i:s'))
		// LARAVEL BUG
		// ->group_by('blogs_pages.cmsblog_id')
		->order_by('blogs.datetime_on', 'asc');
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

		if($self>0) {
			unset($slugs[$self]);
		}

		return $slugs;

	}

	//PAGE GENERAL POSITION DROPDOWN
	public static function select_page_slug($lang, $extra = 1, $self = 0, $first = true)
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
					->where_extra_id($extra)
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
			if($key != LANG) $select[$key] = $value;
		}

		return $select;

	}

	//GET PAGE SLUG
	public static function get_page_slug($pid)
	{
		
		if(!empty($pid)) {
			
			$page = self::find($pid);

			return str_replace('//', '/', $page->slug . '/');
		}
		
		return '/';

	}

	public static function update_child_slugs($page_id, $parent = '', $slug = '/')
	{

		if(!empty($page_id)) {

			//GET PAGE DATA
			$pages = self::where_parent_id($page_id)->get();

			foreach ($pages as $page) {

				$pag = self::find($page->id);

				$slg = $parent . '/' . $slug . '/' . Str::slug($page->name);
				$slg = str_replace('//', '/', $slg);
				$slg = str_replace('//', '/', $slg);

				$pag->slug = $slg;
				$pag->save();

				call_user_func_array('self::update_child_slugs', array($page->id, $slg));

			}

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
				->order_by('order_id', 'asc')
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
				->order_by('order_id', 'asc')
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
				->order_by('order_id', 'asc')
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
				->order_by('order_id', 'asc')
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
				->order_by('order_id', 'asc')
				->order_by('id', 'asc')
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('self::recursive_pages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		return $new_data;

	}


	// CREATE RECURSIVE PAGE LIST ITEM - ADMIN
	public static function page_list_recursive($item, $parent = 0)
	{
		$has_child = false;

		foreach ($item as $page) {

			$_session = Session::get('keep_open_item');
			
			$open = ($_session['parent_id'] == $page->parent_id) ? ' open' : '';

			if($page->parent_id == $parent) {

				if(!$has_child) {
					echo '<ol class="unstyled list'.$open.'" rel="'.$page->parent_id.'">';
					$has_child = true;
				}

				// RENDERS page_item VIEW
				$_view =  View::make('cms::interface.pages.page_item');
				$_view['item'] = $item;
				$_view['page'] = $page;
				$_view['_active'] = $_session['page_id'];

				echo $_view;

			}			

		}

		if($has_child) {
			echo '</ol>';
		}

	}



	// LAYOUT PREVIEW PRE-PROCESS
	public static function preview_layout_create($layout)
	{
		// GET LAYOUT
		$preview_layout_view = View::make('cms::theme.'.Config::get('cms::settings.theme').'.layouts.'.$layout);

		// LOAD CONTENT
		$preview_layout = file_get_contents($preview_layout_view->path);

		// STRIP HTML
		$preview_layout = strip_tags($preview_layout, "<div>");

		// REPLACE ARRAY
		$replace = array(
			'div' => 'div rel="preview"',
			'container' => 'container percent100',
			'row' => 'row-fluid top10',
			'{{' => '<span>',
			'}}' => '</span>'
		);

		// LAYOUT TEMPLATE ARRAY
		$layout_array = Config::get('cms::theme.layout_'.$layout);

		foreach ($replace as $key => $value) {

			// LOOP REPLACE
			$preview_layout = str_replace($key, $value, $preview_layout);

		}

		foreach ($layout_array as $key => $value) {

			// LOOP REPLACE LAYOUT TAGS
			$preview_layout = str_replace('$'.$key, $value, $preview_layout);

		}

		return $preview_layout;

	}


}
