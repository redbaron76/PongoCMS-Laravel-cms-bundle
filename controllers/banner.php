<?php

class Cms_Banner_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL BANNERS
    public function get_index($lang = LANG)
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('banner', 'bundles/cms/js/sections/banner_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.banners', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET DATA
		$data = CmsBanner::with('files')
		->where_lang($lang)
		->order_by('name', 'asc')
		->get();

		$this->layout->content = View::make('cms::interface.pages.banner_list')
		->with('data', $data)
		->with('lang', $lang);

    }

    //NEW BANNER
    public function get_new($lang)
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

		//DATETIME PICKER
		Asset::container('header')->add('jqueryuicss', 'bundles/cms/css/jquery.ui.css', 'main');
		if(LANG !== 'en') Asset::container('footer')->add('local', 'bundles/cms/js/i18n/jquery.ui.datepicker-'.LANG.'.js', 'jquery');
		Asset::container('footer')->add('datepicker', 'bundles/cms/js/jquery.datepicker.js', 'local');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('banner', 'bundles/cms/js/sections/banner_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.banner_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET FILE DATA
		$files = CmsFile::where_is_image(1)
				->where_is_valid(1)
				->order_by('name', 'asc')
				->order_by('id', 'asc')
				->paginate(Config::get('cms::settings.pag'));

		$this->layout->content = View::make('cms::interface.pages.banner_new_edit')
		->with('title', LL('cms::title.banner_new', CMSLANG))
		->with('banner_id', '')
		->with('banner_lang', $lang)
		->with('banner_name', '')
		->with('files', $files)
		->with('files_select', array());

    }

    //EDIT FILE
    public function get_edit($id)
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

		//DATETIME PICKER
		Asset::container('header')->add('jqueryuicss', 'bundles/cms/css/jquery.ui.css', 'main');
		if(LANG !== 'en') Asset::container('footer')->add('local', 'bundles/cms/js/i18n/jquery.ui.datepicker-'.LANG.'.js', 'jquery');
		Asset::container('footer')->add('datepicker', 'bundles/cms/js/jquery.datepicker.js', 'local');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('banner', 'bundles/cms/js/sections/banner_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.banner_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET BANNER DATA
		$banner = CmsBanner::with(array('files'))->find($id);

		//GET FILE DATA
		$files = CmsFile::where_is_image(1)
				->where_is_valid(1)
				->order_by('name', 'asc')
				->order_by('id', 'asc')
				->paginate(Config::get('cms::settings.pag'));

		$this->layout->content = View::make('cms::interface.pages.banner_new_edit')
		->with('title', LL('cms::title.banner_edit', CMSLANG))
		->with('banner_id', $id)
		->with('banner_lang', $banner->lang)
		->with('banner_name', $banner->name)
		->with('files', $files)
		->with('files_select', $banner->files);

    }

    //DETELE BANNER
    public function post_delete()
    {
    	if(Input::has('banner_id')) {

			$bid = Input::get('banner_id');

			$banner = CmsBanner::find($bid);

			//CHECK IF BANNER EXISTS

			if(!empty($banner)) {

				//DELETE FROM DB
				$banner->files()->delete();
				$banner->delete();

				Notification::success(LL('cms::alert.delete_banner_success', CMSLANG, array('banner' => $banner->name)), 1500);

				return Redirect::to_action('cms::banner');

			} else {

				Notification::error(LL('cms::alert.delete_banner_error', CMSLANG), 2500);

				return Redirect::to_action('cms::banner');				

			}

		} else {

			Notification::error(LL('cms::alert.delete_banner_error', CMSLANG), 1500);

			return Redirect::to_action('cms::banner');
		}
    }

}
