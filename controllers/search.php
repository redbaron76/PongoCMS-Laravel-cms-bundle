<?php

class Cms_Search_Controller extends Cms_Searchbase_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}


	//GET SEARCH ENGINE BLOG
	public function action_search_blog()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			//LOAD JS LIBS
			Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
			Asset::container('footer')->add('blogs', 'bundles/cms/js/sections/blogs_list.js', 'cms');

			if(Input::has('q')) {

				$q = Input::get('q');

				$this->layout->header_data = array(
					'title' => $q
				);

				$this->layout->top_data = array(
					'search' => '/cms/blog/search',
					'q' => $q
				);

				// CHECK IF DATE
				if(substr_count($q, '/') >= 2) {

					// CHECK IF DATE RANGE
					if((substr_count($q, '/') == 4) and (substr_count($q, ' ') == 1)) {						

						$date_arr = explode(' ', $q);

						$from = date2Db($date_arr[0]) . ' 00:00:00';
						$to = date2Db($date_arr[1]) . ' 23:59:59';						

						// FROM DATE $from TO DATE $to
						$data = CmsBlog::with('user')
						->where_lang(LANG)
						->where('datetime_on', '>=', $from)
						->where('datetime_on', '<=', $to)
						->order_by('datetime_on', 'asc')
						->order_by('name', 'asc')
						->order_by('slug', 'asc')
						->order_by('title', 'asc')
						->paginate(Config::get('cms::settings.pag'));

					} else {

						// FROM DATE XX
						$data = CmsBlog::with('user')
						->where_lang(LANG)
						->where('datetime_on', 'LIKE', date2Db($q).'%')
						->order_by('datetime_on', 'asc')
						->order_by('name', 'asc')
						->order_by('slug', 'asc')
						->order_by('title', 'asc')
						->paginate(Config::get('cms::settings.pag'));

					}

				} else {

					//GET PAGE DATA
					$data = CmsBlog::with('user')
					->where_lang(LANG)
					->where('name', 'LIKE', '%'.$q.'%')
					->or_where('slug', 'LIKE', '%'.$q.'%')
					->or_where('title', 'LIKE', '%'.$q.'%')
					->order_by('datetime_on', 'asc')
					->order_by('name', 'asc')
					->order_by('slug', 'asc')
					->order_by('title', 'asc')
					->paginate(Config::get('cms::settings.pag'));

				}

				
				
				$this->layout->content = View::make('cms::interface.pages.blog_list')
									 ->with('data', $data)
									 ->with('lang', '');
			} else {

				$this->layout->header_data = array(
					'title' => LL('cms::title.blogs', CMSLANG)
				);

				$this->layout->top_data = array(
					'search' => '/cms/blog/search',
					'q' => ''
				);

				//GET PAGE DATA
				$data = CmsBlog::with('user')
				->where_lang(LANG)
				->order_by('updated_at', 'desc')
				->order_by('created_at', 'desc')
				->paginate(Config::get('cms::settings.pag'));
				
				$this->layout->content = View::make('cms::interface.pages.blog_list')
									 ->with('data', $data)
									 ->with('lang', LANG);

			}

		}
	}


	//GET SEARCH ENGINE FILES
	public function action_search_file()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			//LOAD FANCYBOX LIBS
			Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
			Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

			//LOAD JS LIBS
			Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
			Asset::container('footer')->add('files', 'bundles/cms/js/sections/files_list.js', 'cms');

			if(Input::has('q')) {

				$q = Input::get('q');

				$this->layout->header_data = array(
					'title' => $q
				);

				$this->layout->top_data = array(
					'search' => '/cms/file/search',
					'q' => $q
				);

				//GET PAGE DATA
				$data = CmsFile::where('name', 'LIKE', '%'.$q.'%')
						->or_where('ext', 'LIKE', '%'.$q.'%')
						->order_by('ext', 'asc')
						->order_by('size', 'desc')
						->order_by('name', 'asc')
						->paginate(Config::get('cms::settings.pag'));
				
				$this->layout->content = View::make('cms::interface.pages.file_list')
				->with('data', $data);

			} else {

				$this->layout->header_data = array(
					'title' => LL('cms::title.files', CMSLANG)
				);

				$this->layout->top_data = array(
					'search' => '/cms/file/search',
					'q' => ''
				);

				//GET DATA
				$data = CmsFile::with('pages')
						->order_by('ext', 'asc')
						->order_by('size', 'desc')
						->order_by('name', 'asc')
						->paginate(Config::get('cms::settings.pag'));

				$this->layout->content = View::make('cms::interface.pages.file_list')
				->with('data', $data);

			}

		}
	}


	//GET SEARCH ENGINE PAGE
	public function action_search_page()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			//LOAD JS LIBS
			Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
			Asset::container('footer')->add('pages', 'bundles/cms/js/sections/pages_list.js', 'cms');

			if(Input::has('q')) {

				$q = Input::get('q');

				$this->layout->header_data = array(
					'title' => $q
				);

				$this->layout->top_data = array(
					'search' => '/cms/page/search',
					'q' => $q
				);

				//GET PAGE DATA
				$data = CmsPage::with('user')
								->where_lang(LANG)
								->where('name', 'LIKE', '%'.$q.'%')
								->or_where('title', 'LIKE', '%'.$q.'%')
								->order_by('name', 'asc')
								->order_by('slug', 'asc')
								->order_by('title', 'asc')
								->paginate(Config::get('cms::settings.pag'));

				$this->layout->content = View::make('cms::interface.pages.page_list')
									 ->with('data', $data)
									 ->with('lang', '');
			} else {

				$this->layout->header_data = array(
					'title' => LL('cms::title.pages', CMSLANG)
				);

				$this->layout->top_data = array(
					'search' => '/cms/page/search',
					'q' => ''
				);

				//GET ALL PAGE DATA
				$data = CmsPage::with('user')
								->where_lang(LANG)
								->order_by('order_id', 'asc')
								->order_by('updated_at', 'desc')
								->paginate(Config::get('cms::settings.pag'));

				$this->layout->content = View::make('cms::interface.pages.page_list')
										 ->with('data', $data)
										 ->with('lang', LANG);

			}

		}
	}


	//GET SEARCH ENGINE ROLE
	public function action_search_role()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			//LOAD JS LIBS
			Asset::container('footer')->add('pages', 'bundles/cms/js/sections/roles_list.js', 'cms');

			if(Input::has('q')) {

				$q = Input::get('q');

				$this->layout->header_data = array(
					'title' => $q
				);

				$this->layout->top_data = array(
					'search' => '/cms/role/search',
					'q' => $q
				);

				//GET PAGE DATA
				$data = CmsRole::where('name', 'LIKE', '%'.$q.'%')
								->or_where('level', 'LIKE', '%'.$q.'%')
								->order_by('name', 'asc')
								->order_by('level', 'asc')
								->get();
				
				$this->layout->content = View::make('cms::interface.pages.role_list')->with('data', $data);

			} else {

				$this->layout->header_data = array(
					'title' => LL('cms::title.roles', CMSLANG)
				);

				$this->layout->top_data = array(
					'search' => '/cms/role/search',
					'q' => ''
				);

				//GET ALL PAGE DATA
				$data = CmsRole::order_by('level', 'desc')->get();

				$this->layout->content = View::make('cms::interface.pages.role_list')->with('data', $data);

			}

		}
	}


	//GET SEARCH ENGINE USERS
	public function action_search_user()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			//LOAD JS LIBS
			Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
			Asset::container('footer')->add('user', 'bundles/cms/js/sections/users_list.js', 'cms');

			if(Input::has('q')) {

				$q = Input::get('q');

				$this->layout->header_data = array(
					'title' => $q
				);

				$this->layout->top_data = array(
					'search' => '/cms/user/search',
					'q' => $q
				);

				//GET PAGE DATA
				$data = CmsUser::where('username', 'LIKE', '%'.$q.'%')
						->or_where('email', 'LIKE', '%'.$q.'%')
						->or_where('role_level', '=', $q)
						->order_by('username', 'asc')
						->order_by('email', 'asc')
						->order_by('role_id', 'asc')
						->paginate(Config::get('cms::settings.pag'));
				
				$this->layout->content = View::make('cms::interface.pages.user_list')
									 ->with('data', $data);

			} else {

				$this->layout->header_data = array(
					'title' => LL('cms::title.users', CMSLANG)
				);

				$this->layout->top_data = array(
					'search' => '/cms/user/search',
					'q' => ''
				);

				//GET ALL PAGE DATA
				$data = CmsUser::with('role')
						->order_by('username', 'asc')
						->order_by('role_level', 'asc')
						->paginate(Config::get('cms::settings.pag'));

				$this->layout->content = View::make('cms::interface.pages.user_list')
				->with('data', $data);

			}

		}
	}


	//GET SEARCH ENGINE TAGS
	public function action_search_tag()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			//LOAD JS LIBS
			Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
			Asset::container('footer')->add('tags', 'bundles/cms/js/sections/tag_list.js', 'cms');

			if(Input::has('q')) {

				$q = Input::get('q');

				$this->layout->header_data = array(
					'title' => $q
				);

				$this->layout->top_data = array(
					'search' => '/cms/tag/search',
					'q' => $q
				);

				//GET PAGE DATA
				$data = CmsTag::where('name', 'LIKE', '%'.$q.'%')
						->order_by('name', 'asc')
						->paginate(Config::get('cms::settings.pag'));
				
				$this->layout->content = View::make('cms::interface.pages.tag_list')
				->with('lang', '')
				->with('data', $data);

			} else {

				$this->layout->header_data = array(
					'title' => LL('cms::title.users', CMSLANG)
				);

				$this->layout->top_data = array(
					'search' => '/cms/tag/search',
					'q' => ''
				);

				//GET ALL TAG DATA
				$data = CmsTag::where_lang(LANG)
						->order_by('name', 'asc')
						->paginate(Config::get('cms::settings.pag'));

				$this->layout->content = View::make('cms::interface.pages.tag_list')
				->with('data', $data)
				->with('lang', LANG);

			}

		}
	}


}
