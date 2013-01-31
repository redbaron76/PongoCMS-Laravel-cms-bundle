<?php

class Cms_Download_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL DOWNLOADS
    public function get_index()
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('gallery', 'bundles/cms/js/sections/download_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.downloads', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET DATA
		$data = CmsDownload::with('files')
				->order_by('name', 'asc')
				->get();

		$this->layout->content = View::make('cms::interface.pages.download_list')
		->with('data', $data);

    }

    //NEW DOWNLOAD
    public function get_new()
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/download_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.download_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$this->layout->content = View::make('cms::interface.pages.download_new_edit')
		->with('title', LL('cms::title.download_new', CMSLANG))
		->with('download_id', '')
		->with('download_name', '')
		->with('files', array());

    }

    //EDIT FILE
    public function get_edit($id)
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/download_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.download_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET DOWNLOAD DATA
		$download = CmsDownload::with(array('files'))->find($id);

		$this->layout->content = View::make('cms::interface.pages.download_new_edit')
		->with('title', LL('cms::title.download_edit', CMSLANG))
		->with('download_id', $id)
		->with('download_name', $download->name)
		->with('files', $download->files);

    }

    //DETELE FILE
    public function post_delete()
    {
    	if(Input::has('download_id')) {

			$did = Input::get('download_id');

			$download = CmsDownload::find($did);

			//CHECK IF DOWNLOAD EXISTS

			if(!empty($download)) {

				//DELETE FROM DB
				$download->files()->delete();
				$download->delete();

				Notification::success(LL('cms::alert.delete_download_success', CMSLANG, array('download' => $download->name)), 1500);

				return Redirect::to_action('cms::download');

			} else {

				Notification::error(LL('cms::alert.delete_download_error', CMSLANG), 2500);

				return Redirect::to_action('cms::download');				

			}

		} else {

			Notification::error(LL('cms::alert.delete_download_error', CMSLANG), 1500);

			return Redirect::to_action('cms::download');
		}
    }

}