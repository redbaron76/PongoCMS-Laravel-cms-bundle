<?php

class Cms_Dashboard_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	public function get_index()
	{
				
		$analytics = Config::get('cms::settings.analytics.profile_id');

		if(!empty($analytics)) {

			//LOAD JS LIBS
			Asset::container('footer')->add('flot', 'bundles/cms/js/jquery.flot.js', 'jquery');
			Asset::container('footer')->add('blog', 'bundles/cms/js/sections/dashboard_list.js', 'cms');

		}

		$this->layout->header_data = array(
			'title' => 'Dashboard'
		);

		$this->layout->top_data = array(
			'search' => false
		);

		
		$files = CmsUtility::mediasize(0);
		$images = CmsUtility::mediasize(1);
		$thumbs = CmsUtility::pathsize(path('public'), Config::get('cms::settings.data').'img/thumb/');
		$cache = CmsUtility::pathsize(path('storage'), 'cache/');
		$total = $files + $images + $thumbs + $cache;


		$this->layout->content = View::make('cms::interface.pages.dashboard')
		->with('files', $files)
		->with('images', $images)
		->with('thumbs', $thumbs)
		->with('cache', $cache)
		->with('total', $total);

	}

	//CHANGE INTERFACE LANG
	public function get_lang($lang)
	{

		//SET NEW INTERFACE LANGUAGE
		Session::put('CMSLANG', $lang);
		return Redirect::to_action('cms::dashboard');

	}


	//CHANGE INTERFACE EDITOR
	public function get_editor($editor)
	{

		//SET NEW INTERFACE EDITOR
		Session::put('EDITOR', $editor);
		return Redirect::to_action('cms::dashboard');

	}


	//GET ANALYTICS DATA
	public function get_analytics_data()
	{
		$flot_datas_visits = array();

		$analytics = Config::get('cms::settings.analytics.profile_id');

		if(!empty($analytics)) {

			$id = Config::get('cms::settings.analytics.id');
			$account = Config::get('cms::settings.analytics.account');
			$password = Config::get('cms::settings.analytics.password');
			$pid = Config::get('cms::settings.analytics.profile_id');

			//CACHE DATA
			if(CACHE) {
				
				$show_data = Cache::remember('analytics_'.$pid, function() use ($pid, $account, $password) {

					$ga = new gapi($account, $password);
					$ga->requestReportData($pid, array('date'), array('visits'), array('date'), null, date("Y-m-d", strtotime("-30 days")), date("Y-m-d"));

					$results = $ga->getResults();

					foreach($results as $result) {

						$flot_datas_visits[] = '['.(strtotime($result->getDate()) * 1000).','.$result->getVisits().']';

					}

					return $show_data = '['.implode(',',$flot_datas_visits).']';

				}, 1440);

			//CACHE DISABLED
			} else {

				$ga = new gapi($account, $password);
				$ga->requestReportData($pid, array('date'), array('visits'), array('date'), null, date("Y-m-d", strtotime("-30 days")), date("Y-m-d"));

				$results = $ga->getResults();

				foreach($results as $result) {

					$flot_datas_visits[] = '['.(strtotime($result->getDate()) * 1000).','.$result->getVisits().']';

				}

				$show_data = '['.implode(',',$flot_datas_visits).']';

			}

			return $show_data;

		}

	}


	//DB BACKUP
	public function get_db_backup()
	{
		$file = CmsUtility::db_backup();
		
		$download_link = path('storage') . 'database/' . $file;
		
		return Response::download($download_link);
	}


	//DELETE CACHE
	public function get_delete_cache($pattern = '*')
	{

		if($pattern != '*') $pattern = $pattern . '*';

		$files = glob(path('storage') . 'cache/' . $pattern);
		
		foreach($files as $file) {
			
			//D($file);
			if($file != '.gitignore') unlink($file);
			
		}
		
		Notification::success(LL('cms::alert.delete_cache_success', CMSLANG, array('cache' => $pattern)), 2500);

		return Redirect::to_action('cms::dashboard');

	}

}

