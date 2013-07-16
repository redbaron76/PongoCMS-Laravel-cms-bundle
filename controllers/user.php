<?php

class Cms_User_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL USERS
    public function get_index()
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
		Asset::container('footer')->add('users', 'bundles/cms/js/sections/users_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.users', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => '/cms/user/search',
			'q' => ''
		);

		//GET DATA
		$data = CmsUser::with('role')
				->order_by('username', 'asc')
				->order_by('role_level', 'asc')
				->paginate(Config::get('cms::settings.pag'));

		$this->layout->content = View::make('cms::interface.pages.user_list')
		->with('data', $data);

    }

    //NEW USER
    public function get_new()
    {

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('users', 'bundles/cms/js/sections/users_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.users_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$this->layout->content = View::make('cms::interface.pages.user_new_edit')
		->with('title', LL('cms::title.users_new', CMSLANG))
		->with('user_id', '')
		->with('user_username', '')
		->with('user_email', '')
		->with('user_role', CmsRole::select_user_roles())
		->with('user_role_selected', null)
		->with('user_lang', Config::get('cms::settings.interface'))
		->with('user_lang_selected', LANG)
		->with('user_editor', Config::get('cms::settings.editor'))
		->with('user_editor_selected', null)
		->with('user_is_valid', true)
		
		// USER DETAILS
		->with('detail_id', '')
		->with('user_name', '')
		->with('user_surname', '')
		->with('user_address', '')
		->with('user_info', '')
		->with('user_number', '')
		->with('user_city', '')
		->with('user_zip', '')
		->with('user_state', '')
		->with('user_country', '')
		->with('user_tel', '')
		->with('user_cel', '');

    }

    //EDIT USER
    public function get_edit($id)
    {

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('users', 'bundles/cms/js/sections/users_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.users_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET PAGE DATA
		$user = CmsUser::find($id);

		$has_details = !is_null($user->details);

		$this->layout->content = View::make('cms::interface.pages.user_new_edit')
		->with('title', LL('cms::title.users_edit', CMSLANG))
		->with('user_id', $id)
		->with('user_username', $user->username)
		->with('user_email', $user->email)
		->with('user_role', CmsRole::select_user_roles())
		->with('user_role_selected', $user->role_id)
		->with('user_lang', Config::get('cms::settings.interface'))
		->with('user_lang_selected', $user->lang)
		->with('user_editor', Config::get('cms::settings.editor'))
		->with('user_editor_selected', $user->editor)
		->with('user_is_valid', (bool) $user->is_valid)

		// USER DETAILS
		->with('detail_id', $has_details ? $user->details->id : '')
		->with('user_name', $has_details ? $user->details->name : '')
		->with('user_surname', $has_details ? $user->details->surname : '')
		->with('user_address', $has_details ? $user->details->address : '')
		->with('user_info', $has_details ? $user->details->info : '')
		->with('user_number', $has_details ? $user->details->number : '')
		->with('user_city', $has_details ? $user->details->city : '')
		->with('user_zip', $has_details ? $user->details->zip : '')
		->with('user_state', $has_details ? $user->details->state : '')
		->with('user_country', $has_details ? $user->details->country : '')
		->with('user_tel', $has_details ? $user->details->tel : '')
		->with('user_cel', $has_details ? $user->details->cel : '');

    }

    //DETELE USER
    public function post_delete()
    {
    	if(Input::has('user_id')) {

			$uid = Input::get('user_id');

			$user = CmsUser::find($uid);

			//CHECK IF USER EXISTS

			if(empty($user)) {

				Notification::error(LL('cms::alert.delete_user_error', CMSLANG), 2500);

				return Redirect::to_action('cms::user');

			} else {

				$user->details->delete();

				$user->delete();

				Notification::success(LL('cms::alert.delete_user_success', CMSLANG, array('user' => $user->username)), 1500);

				return Redirect::to_action('cms::user');

			}

		} else {

			Notification::error(LL('cms::alert.delete_user_error', CMSLANG), 1500);

			return Redirect::to_action('cms::user');
		}
    }
    

}
