<?php

class Cms_Blog_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL PAGES
	public function get_index($lang = LANG)
	{

		//LOAD JS LIBS
		Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
		Asset::container('footer')->add('blog', 'bundles/cms/js/sections/blogs_list.js', 'cms');

		//SET ACTIVE LANG
		Session::put('LANG', $lang);

		$this->layout->header_data = array(
			'title' => LL('cms::title.blogs', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => '/cms/blog/search',
			'q' => ''
		);

		//GET PAGE DATA
		$data = CmsBlog::with(array('user'))
				->where_lang($lang)
				->order_by('updated_at', 'desc')
				->order_by('created_at', 'desc')
				->paginate(Config::get('cms::settings.pag'));

		$this->layout->content = View::make('cms::interface.pages.blog_list')
		->with('data', $data)
		->with('lang', $lang);

	}


	//NEW PAGE FORM
	public function get_new($lang)
	{		

		//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('count', 'bundles/cms/js/jquery.charcount.js', 'jquery');
		Asset::container('footer')->add('elastic', 'bundles/cms/js/jquery.elastic.js', 'jquery');
		Asset::container('footer')->add('slug', 'bundles/cms/js/jquery.stringtoslug.js', 'jquery');

		//CKEDITOR
		if(EDITOR == 'ckeditor') {
			Asset::container('footer')->add('ckeditor', 'bundles/cms/ckeditor/ckeditor.js', 'form');
			Asset::container('footer')->add('jqadapter', 'bundles/cms/ckeditor/adapters/jquery.js', 'form');
			Asset::container('footer')->add('ckcms', 'bundles/cms/js/ck.cms.js', 'jqadapter');
		}

		//MARKITUP
		if(EDITOR == 'markitup') {
			Asset::container('footer')->add('markitup', 'bundles/cms/markitup/jquery.markitup.js', 'form');
			Asset::container('footer')->add('sethtml', 'bundles/cms/markitup/sets/html/set.js', 'markitup');
			Asset::container('footer')->add('ckcms', 'bundles/cms/js/ck.cms.js', 'jqadapter');
			Asset::container('header')->add('csshtml', 'bundles/cms/markitup/sets/html/style.css');
			Asset::container('header')->add('cssmarkitup', 'bundles/cms/markitup/skins/markitup/style.css');
		}

		//PLUPLOAD
		Asset::container('footer')->add('plupload', 'bundles/cms/js/plupload.js', 'jquery');
		Asset::container('footer')->add('plupload_html4', 'bundles/cms/js/plupload.html4.js', 'plupload');
		Asset::container('footer')->add('plupload_html5', 'bundles/cms/js/plupload.html5.js', 'plupload');

		//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

		//LOAD AUTOSUGGEST LIBS
		Asset::container('header')->add('autosuggestcss', 'bundles/cms/css/autosuggest.css', 'main');
		Asset::container('footer')->add('autosuggest', 'bundles/cms/js/jquery.autosuggest.js', 'jquery');

		//DATETIME PICKER
		Asset::container('header')->add('jqueryuicss', 'bundles/cms/css/jquery.ui.css', 'main');
		if(CMSLANG !== 'en') Asset::container('footer')->add('local', 'bundles/cms/js/i18n/jquery.ui.datepicker-'.CMSLANG.'.js', 'jquery');
		Asset::container('footer')->add('datepicker', 'bundles/cms/js/jquery.datepicker.js', 'local');		
		Asset::container('footer')->add('timepicker', 'bundles/cms/js/jquery.timepicker.js', 'datepicker');


		//SORTING
		//Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		//Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/blogs_edit.js', 'cms');

		//SET ACTIVE LANG
		Session::put('LANG', $lang);

		$this->layout->header_data = array(
			'title' => LL('cms::title.blog_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$data = array(
			'lang' => $lang
		);

		//GET EXTRA ID

		$extra_ids = Config::get('cms::settings.extra_id');

		$this->layout->content = View::make('cms::interface.pages.blog_new_edit')
		->with('role_fail', false)
		->with('title', LL('cms::title.blog_new', CMSLANG))
		->with('blog_id', '')
		->with('page_id', '')
		->with('blog_lang', $lang)
		->with('blog_name', '')
		->with('blog_parent', CmsPage::select_page_slug($lang, array_search('blogs', $extra_ids)))
		->with('blog_parent_selected', 0)
		->with('blog_slug', '')
		->with('blog_parent_slug', '/')
		->with('blog_zones', CmsElement::select_zone())
		->with('blog_zone_selected', false)
		->with('blog_is_valid', true)
		->with('blog_date_on', '')
		->with('blog_date_off', '')
		->with('blog_title', '')
		->with('blog_preview', '')
		->with('blog_text', '')
		->with('blog_keyw', '')
		->with('blog_descr', '')
		->with('blog_tags', '')
		->with('files', array())
		->with('pagedata', array())
		->with('pagerels', array())
		->with('blogdata', array())
		->with('blogrels', array());

	}


	//NEW PAGE FORM
	public function get_edit($id)
	{		

		//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('count', 'bundles/cms/js/jquery.charcount.js', 'jquery');
		Asset::container('footer')->add('elastic', 'bundles/cms/js/jquery.elastic.js', 'jquery');
		Asset::container('footer')->add('slug', 'bundles/cms/js/jquery.stringtoslug.js', 'jquery');

		Asset::container('footer')->add('ckcms', 'bundles/cms/js/ck.cms.js', 'jqadapter');

		//CKEDITOR
		if(EDITOR == 'ckeditor') {
			Asset::container('footer')->add('ckeditor', 'bundles/cms/ckeditor/ckeditor.js', 'form');
			Asset::container('footer')->add('jqadapter', 'bundles/cms/ckeditor/adapters/jquery.js', 'form');
			Asset::container('footer')->add('ckcms', 'bundles/cms/js/ck.cms.js', 'jqadapter');
		}

		//MARKITUP
		if(EDITOR == 'markitup') {
			Asset::container('footer')->add('markitup', 'bundles/cms/markitup/jquery.markitup.js', 'form');
			Asset::container('footer')->add('sethtml', 'bundles/cms/markitup/sets/html/set.js', 'markitup');
			Asset::container('footer')->add('ckcms', 'bundles/cms/js/ck.cms.js', 'jqadapter');
			Asset::container('header')->add('csshtml', 'bundles/cms/markitup/sets/html/style.css');
			Asset::container('header')->add('cssmarkitup', 'bundles/cms/markitup/skins/markitup/style.css');
		}

		//PLUPLOAD
		Asset::container('footer')->add('plupload', 'bundles/cms/js/plupload.js', 'jquery');
		Asset::container('footer')->add('plupload_html4', 'bundles/cms/js/plupload.html4.js', 'plupload');
		Asset::container('footer')->add('plupload_html5', 'bundles/cms/js/plupload.html5.js', 'plupload');

		//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

		//LOAD AUTOSUGGEST LIBS
		Asset::container('header')->add('autosuggestcss', 'bundles/cms/css/autosuggest.css', 'main');
		Asset::container('footer')->add('autosuggest', 'bundles/cms/js/jquery.autosuggest.js', 'jquery');

		//DATETIME PICKER
		Asset::container('header')->add('jqueryuicss', 'bundles/cms/css/jquery.ui.css', 'main');
		if(CMSLANG !== 'en') Asset::container('footer')->add('local', 'bundles/cms/js/i18n/jquery.ui.datepicker-'.CMSLANG.'.js', 'jquery');
		Asset::container('footer')->add('datepicker', 'bundles/cms/js/jquery.datepicker.js', 'local');		
		Asset::container('footer')->add('timepicker', 'bundles/cms/js/jquery.timepicker.js', 'datepicker');

		//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/blogs_edit.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.blog_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		if(!empty($id)){

			//GET BLOG DATA
			$blog = CmsBlog::with(array('pages', 'blogrels'))->find($id);

			$pivot = DB::table('blogs_pages')->where_cmsblog_id($id)->where_is_default(1)->first();

			//PAGE ID
			$page_id = !empty($pivot) ? $pivot->cmspage_id : '';

			//FILES OF PAGE
			$files = !empty($pivot) ? CmsPage::find($page_id)->files : array();

			//ROLE FAIL
			$role_fail = !empty($pivot) ? CmsRole::role_fail($page_id) : true;
			
			if(!empty($blog)) {

				//GET EXTRA ID

				$extra_ids = Config::get('cms::settings.extra_id');

				//GET PAGE DATA
				$pagedata = CmsPage::where_lang($blog->lang)
						->where_parent_id(0)
						->where_extra_id(array_search('blogs', $extra_ids))
						->order_by('lang', 'asc')
						->order_by('is_home', 'desc')
						->order_by('order_id', 'asc')
						->get();

				$new_data = array();

				foreach ($pagedata as $obj) {
					$new_data[$obj->id] = $obj;
					$recursive = call_user_func_array('CmsPage::recursive_pages', array($obj->id));
					$new_data = ($new_data + $recursive);
				}

				//GET BLOG DATA
				$blogdata = CmsBlog::where_lang($blog->lang)
						->where('id', '<>', $id)
						->where_is_valid(1)
						->order_by('datetime_on', 'desc')
						->order_by('name', 'desc')
						->paginate(Config::get('cms::settings.pag'));

				if(empty($new_data)) $new_data = array();

				$this->layout->content = View::make('cms::interface.pages.blog_new_edit')
				->with('role_fail', $role_fail)
				->with('title', LL('cms::title.blog_edit', CMSLANG))
				->with('blog_id', $id)
				->with('page_id', $page_id)
				->with('blog_lang', $blog->lang)
				->with('blog_name', $blog->name)
				->with('blog_parent', CmsPage::select_page_slug($blog->lang, array_search('blogs', $extra_ids)))
				->with('blog_parent_selected', $page_id)
				->with('blog_slug', substr($blog->slug, 1))
				->with('blog_parent_slug', CmsPage::get_page_slug($page_id))
				->with('blog_zones', CmsElement::select_zone($page_id))
				->with('blog_zone_selected', $blog->zone)
				->with('blog_is_valid', (bool) $blog->is_valid)
				->with('blog_date_on', $blog->get_datetime_on())
				->with('blog_date_off', $blog->get_datetime_off())
				->with('blog_title', $blog->title)
				->with('blog_preview', $blog->preview)
				->with('blog_text', $blog->text)
				->with('blog_keyw', $blog->keyw)
				->with('blog_descr', $blog->descr)
				->with('blog_tags', '')
				->with('files', $files)
				->with('pagedata', $new_data)
				->with('pagerels', $blog->pages)
				->with('blogdata', $blogdata)
				->with('blogrels', $blog->blogrels);

			}

		}

	}

	//POST DELETE BLOG
	public function post_delete()
	{
		if(Input::has('blog_id')) {

			$bid = Input::get('blog_id');

			$blog = CmsBlog::find($bid);

			//CHECK IF BLOG EXISTS

			if(!empty($blog)) {

				//OK, DELETE
				$blog->pages()->delete();
				$blog->delete();

				Notification::success(LL('cms::alert.delete_blog_success', CMSLANG, array('blog' => $blog->name)), 2500);

				return Redirect::to_action('cms::blog', array($blog->lang));				

			} else {

				Notification::error(LL('cms::alert.delete_blog_error', CMSLANG), 2500);

				return Redirect::to_action('cms::blog', array(LANG));

			}

		} else {

			Notification::error(LL('cms::alert.delete_blog_error', CMSLANG), 2500);

			return Redirect::to_action('cms::blog', array(LANG));
		}
	}

	

	//GET BLOG POPOVER DETAILS
	public function post_popover_details()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			if(Input::has('id')) {

				$bid = Input::get('id');

				//CACHE DATA
				if(CACHE) {
					$blog = Cache::remember('blog_'.$bid.'_details', function() use ($bid) {
						return CmsBlog::with(array('user', 'pages', 'blogrels'))->find($bid);
					}, 60);
				} else {
					$blog = CmsBlog::with(array('user', 'pages'))->find($bid);
				}

				return View::make('cms::interface.partials.blog_details')
				->with('author', $blog->user->username)
				->with('created_at', $blog->created_date)
				->with('updated_at', $blog->updated_date)
				->with('datetime_on', $blog->dt_on)
				->with('datetime_off', $blog->dt_off)
				->with('is_valid', (bool) $blog->is_valid)
				->with('lang', $blog->lang)
				->with('pagerels', $blog->pages)
				->with('blogrels', $blog->blogrels);

			}

		}
		
	}

}
