<?php

class Cms_Site_Controller extends Cms_Base_Controller {

	/*
	|--------------------------------------------------------------------------
	| FRONTEND - Site controller
	|--------------------------------------------------------------------------
	|
	|
	*/

	//RESTFUL CONTROLLER
	public $restful = true;


	//FILTERS
	public function __construct()
	{
		parent::__construct();

		$this->filter('before', 'init');
	}


	//SHOW USER LOGIN
	public function get_sitemap($lang = LANG)
	{

		//GET PAGE DATA
		$data = CmsPage::with(array('files' => function($query) {
			$query->where_is_image(1)->where_is_valid(1);
		}))
				->where_parent_id(0)
				->where_lang($lang)
				->order_by('is_home', 'desc')
				->order_by('slug', 'asc')
				->get();

		$new_data = array();

		foreach ($data as $value) {
			$new_data[$value->id] = $value;
		}

		foreach ($new_data as $page) {
			$recursive = call_user_func_array('CmsPage::recursive_site_sitemap', array($page->id));
			$new_data = array_insert($new_data, $page->id, $recursive);
		}

		$view = View::make('cms::theme.'.THEME.'.templates.sitemap')
		->with('encoding', '<?xml version="1.0" encoding="UTF-8" ?>')
		->with('base', Config::get('application.url'))
		->with('data', $new_data);

		$headers = array('Content-Type' => 'text/xml');

    	return Response::make($view, 200, $headers);

	}


	//SHOW USER LOGIN
	public function get_login()
	{

		$html = View::make('cms::theme.'.THEME.'.templates.'.TEMPLATE)
		->with('title', LL('user.user_title', SITE_LANG))
		->with('keyw', 'login')
		->with('descr', 'login')
		->nest('header', 'cms::theme.'.THEME.'.partials.header_default')
		->nest('layout', 'cms::theme.'.THEME.'.user.login')
		->nest('footer', 'cms::theme.'.THEME.'.partials.footer_default');

		CmsRender::clean_code($html);

	}

	//PERFORM USER LOGIN
	public function post_login()
	{

		//POST LOGIN

		$credentials = array(
			'cms' => false,
			'username' => Input::get('username'),
			'password' => Input::get('password'),
			'remember' => (bool) Input::get('remember')
		);

		//CHECK CREDENTIALS
		if(Auth::attempt($credentials)) {

			//SUCCESS LOGIN
			return Redirect::home();

		} else {

			//ERROR LOGIN
			return Redirect::to_action('user@login')
			->with_input('only', array('username'));

		}

	}

	//PERFORM USER LOGOUT
	public function get_logout()
	{
		
		Auth::logout();

		return Redirect::home();

	}



}
