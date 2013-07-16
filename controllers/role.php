<?php

class Cms_Role_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL ROLES
    public function get_index()
    {

		$this->layout->header_data = array(
			'title' => LL('cms::title.roles', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => '/cms/role/search',
			'q' => ''
		);

		//GET DATA
		$data = CmsRole::order_by('level', 'desc')->get();

		$this->layout->content = View::make('cms::interface.pages.role_list')
		->with('data', $data);

    }

	//ADD NEW ROLE
	public function get_new()
	{

		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/roles_edit.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.role_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$this->layout->content = View::make('cms::interface.pages.role_new_edit')
		->with('title', LL('cms::title.role_new', CMSLANG))
		->with('role_id', '')
		->with('role_name', '')
		->with('role_level', CmsRole::select_levels())
		->with('role_level_selected', null);

	}

	//EDIT ROLE
    public function get_edit($id)
    {

    	Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/roles_edit.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.role_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		if(!empty($id)){

			//GET ROLE DATA
			$role = CmsRole::find($id); 
			
			if(!empty($role)) {

				$this->layout->content = View::make('cms::interface.pages.role_new_edit')
				->with('title', LL('cms::title.role_edit', CMSLANG))
				->with('role_id', $id)
				->with('role_name', $role->name)
				->with('role_level', CmsRole::select_levels())
				->with('role_level_selected', $role->level);

			} else {

				$this->layout->content = View::make('cms::interface.pages.not_found')
									 ->with('message', LL('cms::alert.not_found', CMSLANG));

			}

		} else {

			$this->layout->content = View::make('cms::interface.pages.not_found')
									 ->with('message', LL('cms::alert.not_found', CMSLANG));

		}
    	
    }

    //POST DELETE PAGE
	public function post_delete()
	{
		if(Input::has('role_id')) {

			$rid = Input::get('role_id');

			$page = CmsPage::where_role_id($rid)->first();

			//CHECK IF ROLE STILL IN USE

			if(!empty($page)) {

				Notification::error(LL('cms::alert.delete_role_stillinuse_error', CMSLANG, array('page' => $page->name)), 2500);

				return Redirect::to_action('cms::role');

			} else {

				$role = CmsRole::find($rid);

				$role->delete();

				Notification::success(LL('cms::alert.delete_role_success', CMSLANG, array('role' => $role->name)), 1500);

				return Redirect::to_action('cms::role');

			}

		} else {

			Notification::error(LL('cms::alert.delete_role_error', CMSLANG), 1500);

			return Redirect::to_action('cms::page');
		}
	}	

}
