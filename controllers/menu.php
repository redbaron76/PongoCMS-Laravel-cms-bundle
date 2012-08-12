<?php

class Cms_Menu_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL FILES
    public function get_index($lang = LANG)
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('menu', 'bundles/cms/js/sections/menus_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.menus', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET DATA
		$data = CmsMenu::with('pages')
		->where_lang($lang)
		->order_by('name', 'asc')
		->get();

		$this->layout->content = View::make('cms::interface.pages.menu_list')
		->with('data', $data)
		->with('lang', $lang);

    }

    //NEW MENU
    public function get_new($lang)
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/menus_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.menu_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET PAGE DATA
		$data = CmsPage::with(array('menus'))
				->where_lang($lang)
				->where_parent_id(0)
				->order_by('lang', 'asc')
				->order_by('is_home', 'desc')
				->order_by('order_id', 'asc')
				->get();		

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('CmsPage::recursive_menuspages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		if(empty($new_data)) $new_data = array();

		$pages = array();

		$this->layout->content = View::make('cms::interface.pages.menu_new_edit')
		->with('title', LL('cms::title.menu_edit', CMSLANG))
		->with('menu_id', '')
		->with('menu_lang', $lang)
		->with('menu_name', '')
		->with('menu_is_nested', false)
		->with('menu_parent_start', CmsPage::select_top_slug($lang, 0, true))
		->with('menu_parent_start_selected', 0)
		->with('pages', $pages)
		->with('menu_pages', $new_data);

    }

    //EDIT FILE
    public function get_edit($id)
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/menus_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.menu_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET MENU DATA
		$menu = CmsMenu::find($id);

		//GET PAGE DATA
		$data = CmsPage::with(array('menus'))
				->where_lang($menu->lang)
				->where_parent_id(0)
				->order_by('lang', 'asc')
				->order_by('is_home', 'desc')
				->order_by('order_id', 'asc')
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('CmsPage::recursive_menuspages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		if(empty($new_data)) $new_data = array();

		//GET PAGES IN MENU
		$pages = CmsMenu::find($id)->pages;

		$this->layout->content = View::make('cms::interface.pages.menu_new_edit')
		->with('title', LL('cms::title.menu_edit', CMSLANG))
		->with('menu_id', $id)
		->with('menu_name', $menu->name)
		->with('menu_lang', $menu->lang)
		->with('menu_is_nested', (bool) $menu->is_nested)
		->with('menu_parent_start', CmsPage::select_top_slug($menu->lang, 0, true))
		->with('menu_parent_start_selected', $menu->parent_start)
		->with('pages', $pages)
		->with('menu_pages', $new_data);

    }

    //DETELE FILE
    public function post_delete()
    {
    	if(Input::has('menu_id')) {

			$mid = Input::get('menu_id');

			$menu = CmsMenu::find($mid);

			//CHECK IF MENU EXISTS

			if(!empty($menu)) {

				//DELETE FROM DB
				$menu->pages()->delete();
				$menu->delete();

				Notification::success(LL('cms::alert.delete_menu_success', CMSLANG, array('menu' => $menu->name)), 1500);

				return Redirect::to_action('cms::menu');

			} else {

				Notification::error(LL('cms::alert.delete_menu_error', CMSLANG), 2500);

				return Redirect::to_action('cms::menu');				

			}

		} else {

			Notification::error(LL('cms::alert.delete_menu_error', CMSLANG), 1500);

			return Redirect::to_action('cms::menu');
		}
    }

}
