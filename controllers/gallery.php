<?php

class Cms_Gallery_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL GALLERIES
    public function get_index()
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('gallery', 'bundles/cms/js/sections/gallery_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.galleries', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET DATA
		$data = CmsGallery::with('files')
				->order_by('name', 'asc')
				->get();

		$this->layout->content = View::make('cms::interface.pages.gallery_list')
		->with('data', $data);

    }

    //NEW GALLERY
    public function get_new()
    {

    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		//LOAD FANCYBOX LIBS
		Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'main');
		Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery');

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/gallery_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.gallery_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$this->layout->content = View::make('cms::interface.pages.gallery_new_edit')
		->with('title', LL('cms::title.gallery_new', CMSLANG))
		->with('gallery_id', '')
		->with('gallery_name', '')
		->with('gallery_thumb', '')
		->with('gallery_thumbs', CmsGallery::select_thumb())
		->with('files', array());

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

    	//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('files', 'bundles/cms/js/sections/gallery_edit.js', 'cms');

    	$this->layout->header_data = array(
			'title' => LL('cms::title.gallery_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET GALLERY DATA
		$gallery = CmsGallery::with(array('files'))->find($id);

		$this->layout->content = View::make('cms::interface.pages.gallery_new_edit')
		->with('title', LL('cms::title.gallery_edit', CMSLANG))
		->with('gallery_id', $id)
		->with('gallery_name', $gallery->name)
		->with('gallery_thumb', $gallery->thumb)
		->with('gallery_thumbs', CmsGallery::select_thumb())
		->with('files', $gallery->files);

    }

    //DETELE GALLERY
    public function post_delete()
    {
    	if(Input::has('gallery_id')) {

			$gid = Input::get('gallery_id');

			$gallery = CmsGallery::find($gid);

			//CHECK IF GALLERY EXISTS

			if(!empty($gallery)) {

				//DELETE FROM DB
				$gallery->files()->delete();
				$gallery->delete();

				Notification::success(LL('cms::alert.delete_gallery_success', CMSLANG, array('gallery' => $gallery->name)), 1500);

				return Redirect::to_action('cms::gallery');

			} else {

				Notification::error(LL('cms::alert.delete_gallery_error', CMSLANG), 2500);

				return Redirect::to_action('cms::gallery');				

			}

		} else {

			Notification::error(LL('cms::alert.delete_gallery_error', CMSLANG), 1500);

			return Redirect::to_action('cms::gallery');
		}
    }

}
