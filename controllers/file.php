<?php

class Cms_File_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL FILES
    public function get_index()
    {

		//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

		//LOAD JS LIBS
		Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/files_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.files', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => '/cms/file/search',
			'q' => ''
		);

		//GET DATA
		$data = CmsFile::with('pages')
				->order_by('name', 'asc')
				->order_by('ext', 'asc')
				->order_by('size', 'desc')				
				->paginate(Config::get('cms::settings.pag'));

		$this->layout->content = View::make('cms::interface.pages.file_list')
		->with('data', $data);

    }

    //EDIT FILE
    public function get_edit($id)
    {

    	//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/files_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.file_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET FILE DATA
		$file = CmsFile::with(array('pages', 'filetexts' => function($query) {

			$query->where('lang', '=', LANG);

		}))->where_id($id)->first();

		if(!empty($file->filetexts)) {

			foreach($file->filetexts as $text) {

				$filetext_alt = (!empty($text)) ? $text->alt : '';
				$filetext_title = (!empty($text)) ? $text->title : '';
				$filetext_caption = (!empty($text)) ? $text->caption : '';
				$filetext_label = (!empty($text)) ? $text->label : '';

			}

		} else {

			$filetext_alt = '';
			$filetext_title = '';
			$filetext_caption = '';
			$filetext_label = '';

		}

		//GET PAGE DATA
		$data = CmsPage::with(array('files'))
				->where_parent_id(0)
				->order_by('lang', 'asc')
				->order_by('is_home', 'desc')
				->order_by('order_id', 'asc')
				->get();

		$banners = CmsBanner::with(array('files'))
				->order_by('lang', 'asc')
				->get();

		$galleries = CmsGallery::with(array('files'))
				->get();

		$downloads = CmsDownload::with(array('files'))
				->get();

		//GET SITEMAP ORDER

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('CmsPage::recursive_filespages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}

		if(empty($new_data)) $new_data = array();

		$this->layout->content = View::make('cms::interface.pages.file_edit')
		->with('title', LL('cms::title.file_edit', CMSLANG))
		->with('file_id', $id)
		->with('is_image', (bool) $file->is_image)
		->with('file_path', $file->path)
		->with('file_name', $file->name)
		->with('file_thumb', $file->thumb)
		->with('file_ext', $file->ext)
		->with('file_pages', $new_data)
		->with('langs', Config::get('cms::settings.langs'))
		->with('filetext_title', $filetext_title)
		->with('filetext_alt', $filetext_alt)
		->with('filetext_caption', $filetext_caption)
		->with('filetext_label', $filetext_label)
		->with('banners', $banners)
		->with('galleries', $galleries)
		->with('downloads', $downloads);

    }

    //DETELE FILE
    public function post_delete()
    {
    	if(Input::has('file_id')) {

			$fid = Input::get('file_id');

			$file = CmsFile::find($fid);

			//CHECK IF FILE EXISTS

			if(!empty($file)) {

				$path = MEDIA_PATH($file->path);

				//DELETE MAIN FILE
				if(file_exists($path)) unlink($path);

				//LOOP ALL THUMBS AND DELETE
				foreach (Config::get('cms::theme.thumb') as $option) {
					
					$thumb = MEDIA_NAME($path, $option['suffix']);
					if(file_exists($thumb)) unlink($thumb);

				}

				//DELETE FROM DB
				$file->pages()->delete();
				$file->filetexts()->delete();
				$file->delete();

				Notification::success(LL('cms::alert.delete_file_success', CMSLANG, array('file' => $file->name)), 1500);

				return Redirect::to_action('cms::file');

			} else {

				Notification::error(LL('cms::alert.delete_file_error', CMSLANG), 2500);

				return Redirect::to_action('cms::file');				

			}

		} else {

			Notification::error(LL('cms::alert.delete_file_error', CMSLANG), 1500);

			return Redirect::to_action('cms::file');
		}
    }


    


	//GET FILE POPOVER DETAILS
	public function post_popover_details()
	{

		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			if(Input::has('id')) {

				$fid = Input::get('id');

				//CACHE DATA
				if(CACHE) {

					$file = Cache::remember('file_'.$fid.'_details', function() use ($fid) {

						return CmsFile::with(array('pages'))->find($fid);

					}, 60);

				} else {

					$file = CmsFile::with(array('pages'))->find($fid);

				}

				return View::make('cms::interface.partials.file_details')
				->with('created_at', $file->created_date)
				->with('updated_at', $file->updated_date)
				->with('size', $file->size)
				->with('w', $file->w)
				->with('h', $file->h)
				->with('is_image', ($file->is_image) ? true : false)
				->with('pagerels', $file->pages);

			}

		}
		
	}

}
