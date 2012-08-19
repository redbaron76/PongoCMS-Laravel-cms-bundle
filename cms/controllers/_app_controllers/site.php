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
		//LOAD VIEW
		$view = View::make('cms::theme.'.THEME.'.partials.markers.login');
		$view['options'] = array();

		//LOAD LAYOUT
		$layout = View::make('cms::theme.'.THEME.'.layouts.default');
		$layout['ZONE1'] = $view;

		//LOAD TEMPLATE
		$html = View::make('cms::theme.'.THEME.'.templates.'.TEMPLATE)
		->nest('header', 'cms::theme.'.THEME.'.partials.header_default')
		->with('layout', $layout)
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

		$back_url = Input::get('back_url', '/');

		//CHECK CREDENTIALS
		if(Auth::attempt($credentials)) {

			//SUCCESS LOGIN
			return Redirect::to($back_url);

		} else {

			//ERROR LOGIN
			return Redirect::to_action('site@login')
			->with_input('only', array('username'))
			->with('back_url', $back_url);

		}

	}

	
	//PERFORM USER LOGOUT
	public function get_logout()
	{
		
		Auth::logout();
		Session::flush();

		return Redirect::home();

	}


	//PERFORM CHANGE LANG
	public function get_lang($lang = SITE_LANG)
	{
		
		if(!empty($lang)) {
			
			Session::put('SITE_LANG', $lang);

			return Redirect::home();

		}

	}


	//SHOW SEARCH PAGE
	public function get_search()
	{

		if(Input::has('q') and Input::has('source')) {

			//GET q
			$q = Input::get('q');

			//GET page
			$p = Input::get('page', 1);

			//ITEMS PER PAGE
			$npp = Config::get('cms::theme.pag');

			//GET SOURCE WHERE TO SEARCH
			$source = Input::get('source');

			$sources = explode('-', $source);

			$results = array();

			//PAGES

			if(is_numeric(array_search('pages', $sources))) {
				
			 	$elements = CmsElement::with(array('pages'))
			 		->where('text', 'LIKE', '%'.$q.'%')
			 		->where_lang(SITE_LANG)
			 		->where_is_valid(1)
			 		->get();

			 	$tot = 0;

			 	foreach ($elements as $key => $element) {

			 		foreach ($element->pages as $page) {

			 			$title = (count($page->title) > 0) ? $page->title : $page->name;

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

			$count_results = count($results);

			$output = array_slice($results, (($npp*$p)-$npp), $npp);

			$paginate = Paginator::make($output, $count_results, $npp);

			//LOAD VIEW
			$view = View::make('cms::theme.'.THEME.'.partials.search_results');
			$view['results'] = $paginate;
			$view['q'] = $q;
			$view['source'] = $source;

			//LOAD LAYOUT
			$layout = View::make('cms::theme.'.THEME.'.layouts.default');
			$layout['ZONE1'] = $view;

			//LOAD TEMPLATE
			$html = View::make('cms::theme.'.THEME.'.templates.'.TEMPLATE)
			->nest('header', 'cms::theme.'.THEME.'.partials.header_default')
			->with('title', $q)
			->with('layout', $layout)
			->nest('footer', 'cms::theme.'.THEME.'.partials.footer_default');

			CmsRender::clean_code($html);

		} else {

			return Redirect::home();

		}

	}


	//PERFORM SEARCH
	public function post_search()
	{

		if(Input::has('q') and Input::has('source')) {

			//GET q
			$q = Input::get('q');

			//GET page
			$p = Input::get('page', 1);

			//ITEMS PER PAGE
			$npp = Config::get('cms::theme.pag');

			//GET SOURCE WHERE TO SEARCH
			$source = Input::get('source');

			$sources = explode('-', $source);

			$results = array();

			//PAGES

			if(is_numeric(array_search('pages', $sources))) {
				
			 	$elements = CmsElement::with(array('pages'))
			 		->where('text', 'LIKE', '%'.$q.'%')
			 		->where_lang(SITE_LANG)
			 		->where_is_valid(1)
			 		->get();

			 	$tot = 0;

			 	foreach ($elements as $key => $element) {

			 		foreach ($element->pages as $page) {

			 			$title = (count($page->title) > 0) ? $page->title : $page->name;

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

			$count_results = count($results);

			$output = array_slice($results, (($npp*$p)-$npp), $npp);

			$paginate = Paginator::make($output, $count_results, $npp);

			//LOAD VIEW
			$view = View::make('cms::theme.'.THEME.'.partials.search_results');
			$view['results'] = $paginate;
			$view['q'] = $q;
			$view['source'] = $source;

			//LOAD LAYOUT
			$layout = View::make('cms::theme.'.THEME.'.layouts.default');
			$layout['ZONE1'] = $view;

			//LOAD TEMPLATE
			$html = View::make('cms::theme.'.THEME.'.templates.'.TEMPLATE)
			->nest('header', 'cms::theme.'.THEME.'.partials.header_default')
			->with('title', $q)
			->with('layout', $layout)
			->nest('footer', 'cms::theme.'.THEME.'.partials.footer_default');

			CmsRender::clean_code($html);

		} else {

			return Redirect::home();

		}


	}



}
