<?php

class Cms_Tag_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL ROLES
    public function get_index($lang = LANG)
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('ias', 'bundles/cms/js/jquery.ias.js', 'jquery');
		Asset::container('footer')->add('tag', 'bundles/cms/js/sections/tag_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.tags', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => '/cms/tag/search',
			'q' => ''
		);

		//GET DATA
		$data = CmsTag::where_lang($lang)
				->order_by('name', 'asc')
				->paginate(Config::get('cms::settings.pag'));

		$this->layout->content = View::make('cms::interface.pages.tag_list')
		->with('data', $data)
		->with('lang', $lang);

    }

	//ADD NEW TAG
	public function get_new($lang)
	{

		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('tags', 'bundles/cms/js/sections/tag_edit.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.tag_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$this->layout->content = View::make('cms::interface.pages.tag_new_edit')
		->with('title', LL('cms::title.tag_new', CMSLANG))
		->with('tag_id', '')
		->with('tag_lang', $lang)
		->with('tag_name', '');

	}

	//EDIT TAG
    public function get_edit($id)
    {

    	Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('tags', 'bundles/cms/js/sections/tag_edit.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.tag_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		if(!empty($id)){

			//GET TAG DATA
			$tag = CmsTag::find($id); 
			
			if(!empty($tag)) {

				$this->layout->content = View::make('cms::interface.pages.tag_new_edit')
				->with('title', LL('cms::title.tag_edit', CMSLANG))
				->with('tag_id', $id)
				->with('tag_lang', $tag->lang)
				->with('tag_name', $tag->name);

			} else {

				$this->layout->content = View::make('cms::interface.pages.not_found')
									 ->with('message', LL('cms::alert.not_found', CMSLANG));

			}

		} else {

			$this->layout->content = View::make('cms::interface.pages.not_found')
									 ->with('message', LL('cms::alert.not_found', CMSLANG));

		}
    	
    }

    //DETELE TAG
    public function post_delete()
    {
    	if(Input::has('tag_id')) {

			$tid = Input::get('tag_id');

			$tag = CmsTag::find($tid);

			//CHECK IF TAG EXISTS

			if(!empty($tag)) {

				$lang = $tag->lang;

				//DELETE FROM DB
				$tag->blogs()->delete();
				$tag->delete();

				Notification::success(LL('cms::alert.delete_tag_success', CMSLANG, array('tag' => $tag->name)), 1500);

				return Redirect::to_action('cms::tag', array($lang));

			} else {

				Notification::error(LL('cms::alert.delete_tag_error', CMSLANG), 2500);

				return Redirect::to_action('cms::tag', array($lang));				

			}

		} else {

			Notification::error(LL('cms::alert.delete_gallery_error', CMSLANG), 1500);

			return Redirect::to_action('cms::tag', array($lang));
		}
    }	

}
