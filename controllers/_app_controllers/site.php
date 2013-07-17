<?php

class Site_Controller extends Base_Controller {

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

	//JS BOOTSTRAP
	public function get_js()
	{

		$view = View::make('cms::interface.partials.js');

		$headers = array('Content-Type' => 'application/javascript');

    	return Response::make($view, 200, $headers);

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
		
		if(Auth::check()) return Redirect::home();

		//LOAD VIEW
		$view = View::make('cms::theme.'.THEME.'.partials.markers.login');
		$view['options'] = array();

		//LOGIN ZONE
		$login_zone = Config::get('cms::theme.login_zone');

		// RENDER THE PAGE
		CmsRender::page('/', array('zone' => $login_zone, 'view' => $view));

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

		$back_url = Input::get('back_url', '/');

		//CHECK CREDENTIALS
		if(Auth::attempt($credentials)) {

			//SUCCESS LOGIN
			return Redirect::to($back_url);

		} else {

			//ERROR LOGIN
			$redirect = (strlen($back_url) > 1) ? Redirect::to(url($back_url)) : Redirect::to_action('site@login');
			$redirect->with_input('only', array('username'));
			$redirect->with('back_url', $back_url);
			$redirect->with('login_error_msg', LL('cms::marker.login_error', LANG, array('user' => Input::get('username')))->get());

			return $redirect;

		}

	}

	
	//PERFORM USER LOGOUT
	public function get_logout()
	{
		
		Auth::logout();
		Session::flush();

		return Redirect::home();

	}


	//SHOW SIGNUP FORM
	public function get_signup()
	{		
		
		//GET SEARCH RESULTS VIEW
		$signup_form = Config::get('cms::theme.signup_form');

		//LOAD VIEW
		$view = View::make('cms::theme.'.THEME.'.partials.'.$signup_form);

		//LOGIN ZONE
		$login_zone = Config::get('cms::theme.login_zone');

		//LOGIN PAGE DETAILS
		$login_template = Config::get('cms::theme.login_template');
		$login_header = Config::get('cms::theme.login_header');
		$login_footer = Config::get('cms::theme.login_footer');
		$login_layout = Config::get('cms::theme.login_layout');

		// RENDER THE PAGE
		CmsRender::page('/', array(
			'template' => $login_template,
			'header' => $login_header,
			'footer' => $login_footer,
			'layout' => $login_layout,
			'zone' => $login_zone,
			'view' => $view)
		);		

	}

	//PERFORM SIGNUP POST
	public function post_signup()
	{

		$input = Input::get();

		//VALIDATION CHECK

		$rules = array(
			'signup_name'  => 'required',
			'signup_surname'  => 'required',
			'signup_address'  => 'required',
			'signup_number'  => 'required',
			'signup_city'  => 'required',
			'signup_zip'  => 'required',
			'signup_state'  => 'required',
			'signup_country'  => 'required',
			'signup_cel'  => 'required',
			'signup_email'  => 'required|email|unique:users,email',
			'password' => 'required|confirmed|min:6'
		);

		$messages = array(
			'required' => LL('cms::validation.required', CMSLANG)->get(),
			'email' => LL('cms::validation.required', CMSLANG)->get(),
			'unique' => LL('cms::validation.unique_account', CMSLANG)->get(),
			'confirmed' => LL('cms::validation.confirmed', CMSLANG)->get()
		);

		$validation = Validator::make($input, $rules, $messages);

		if ($validation->fails())
		{
			return Redirect::to_action('site@signup')->with_input()
			->with('signup_name_error', $validation->errors->first('signup_name'))
			->with('signup_surname_error', $validation->errors->first('signup_surname'))
			->with('signup_address_error', $validation->errors->first('signup_address'))
			->with('signup_number_error', $validation->errors->first('signup_number'))
			->with('signup_city_error', $validation->errors->first('signup_city'))
			->with('signup_state_error', $validation->errors->first('signup_state'))
			->with('signup_zip_error', $validation->errors->first('signup_zip'))
			->with('signup_country_error', $validation->errors->first('signup_country'))
			->with('signup_cel_error', $validation->errors->first('signup_cel'))
			->with('signup_email_error', $validation->errors->first('signup_email'))
			->with('signup_password_error', $validation->errors->first('password'));
		}

		// OK, CREATE ACCOUNT

		$role_id = 4;	// AS cms::settings.roles.user indexof

		$user = new CmsUser();

		$user->role_id = $role_id;
		$user->username = $input['signup_email'];
		$user->email =  $input['signup_email'];
		$user->password = Hash::make($input['password']);
		$user->role_level = Config::get('cms::settings.roles.user');
		$user->lang = LANG;
		$user->is_valid = 1;

		$user->save();

		$uid = $user->id;

		// SAVE DETAILS

		$details = new CmsUserDetail();

		$details->user_id = $uid;
		$details->name = $input['signup_name'];
		$details->surname = $input['signup_surname'];
		$details->address = $input['signup_address'];
		$details->info = '';
		$details->number = $input['signup_number'];
		$details->city = $input['signup_city'];
		$details->zip = $input['signup_zip'];
		$details->state = $input['signup_state'];
		$details->country = $input['signup_country'];
		$details->tel = $input['signup_tel'];
		$details->cel = $input['signup_cel'];

		$details->save();

		// SEND MAIL

		// LOAD MAIL VIEW - NEED SWIFTMAILER BUNDLE for Laravel

		$mail_view = View::make('cms::theme.'.THEME.'.partials.mail_signup');
		$mail_view['name'] = $input['signup_name'];
		$mail_view['username'] = $input['signup_email'];
		$mail_view['password'] = $input['password'];

		// GET MAIL TEMPLATE

		$html = View::make('cms::theme.'.THEME.'.templates.mail')->with('content', $mail_view);

		// OK, SEND A MAIL

		$mailer = IoC::resolve('mailer');

		// Construct the message
		$message = Mail::prepare(
			$html,
			Config::get('cms::theme.email_data.signup_subject'),
			$to = array(
				$input['signup_email']
			),
			$bcc = Config::get('cms::theme.email')
		);

		// Send the email
		$mailer->send($message);

		Session::flash('account_created', true);

		return Redirect::to_action('site@login')->with_input('only', array('signup_email'));;

	}


	//PERFORM CHANGE LANG
	public function get_lang($lang = SITE_LANG)
	{
		
		if(!empty($lang)) {
			
			Session::put('SITE_LANG', $lang);

			return Redirect::home();

		}

	}


	//SHOW SEARCH PAGE AFTER PAGINATION
	public function get_search()
	{

		// USING POST_SEARCH METHOD
		static::post_search();

	}


	//PERFORM SEARCH
	public function post_search()
	{

		if(Input::has('q') and Input::has('source')) {

			//GET q
			$q = Input::get('q');

			//GET page
			$p = Input::get('page', 1);

			// GET FROM WHERE
			if(Input::has('url')) Session::put('URL', Input::get('url', SLUG_FULL));

			$url = Session::get('URL', '/');

			//ITEMS PER PAGE
			$npp = Config::get('cms::theme.site_pag');

			//GET SOURCE WHERE TO SEARCH
			$source = Input::get('source');

			$sources = explode('-', $source);

			$results = array();

			//PAGES

			$tot = 0;

			if(is_numeric(array_search('pages', $sources))) {
				
			 	$elements = CmsElement::with(array('pages'))
			 		->where('text', 'LIKE', '%'.$q.'%')
			 		->where_lang(SITE_LANG)
			 		->where_is_valid(1)
			 		->get();

			 	foreach ($elements as $key => $element) {

			 		foreach ($element->pages as $page) {

			 			$title = (strlen($page->title) > 0) ? $page->title : $page->name;

			 			$results[$key+1]['source'] = LL('cms::label.pages', SITE_LANG)->get();
			 			$results[$key+1]['title'] = $title;
			 			$results[$key+1]['slug'] = $page->slug;
			 			$results[$key+1]['descr'] = $page->descr;

			 		}

			 		$tot ++;

			 	}

			}

			if(is_numeric(array_search('blogs', $sources))) {
				
			 	$blogs = CmsBlog::with(array('pages'))
			 		->where('name', 'LIKE', '%'.$q.'%')
			 		->or_where('preview', 'LIKE', '%'.$q.'%')
			 		->or_where('text', 'LIKE', '%'.$q.'%')
			 		->where_lang(SITE_LANG)
			 		->where_is_valid(1)
			 		->get();

			 	foreach ($blogs as $key => $blog) {

			 		foreach ($blog->pages as $page) {

			 			$title = $blog->name;

			 			$results[$tot+$key+1]['source'] = LL('cms::label.blogs', SITE_LANG)->get();
			 			$results[$tot+$key+1]['title'] = $title;
			 			$results[$tot+$key+1]['slug'] = $page->slug . $blog->slug;
			 			$results[$tot+$key+1]['descr'] = $page->descr;

			 		}

			 		$tot ++;

			 	}

			}

			$unique_results = array_unique($results, SORT_REGULAR);

			$count_results = count($unique_results);

			$output = array_slice($unique_results, (($npp*$p)-$npp), $npp);

			$paginate = Paginator::make($output, $count_results, $npp);

			//GET SEARCH RESULTS VIEW
			$search_results = Config::get('cms::theme.search_results');

			//LOAD VIEW
			$view = View::make('cms::theme.'.THEME.'.partials.'.$search_results);
			$view['results'] = $paginate;
			$view['q'] = $q;
			$view['source'] = $source;

			//LOAD ZONE TO INJECT IN
			$search_zone = Config::get('cms::theme.search_zone');

			//SEARCH PAGE DETAILS
			$search_template = Config::get('cms::theme.search_template');
			$search_header = Config::get('cms::theme.search_header');
			$search_footer = Config::get('cms::theme.search_footer');
			$search_layout = Config::get('cms::theme.search_layout');

			// RENDER THE PAGE
			CmsRender::page($url, array(
				'template' => $search_template,
				'header' => $search_header,
				'footer' => $search_footer,
				'layout' => $search_layout,
				'zone' => $search_zone,
				'view' => $view)
			);

		} else {

			return Redirect::home();

		}


	}



}
