<?php

class Cms_Translation_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

	//LIST ALL ROLES
    public function get_index($lang_to = 'en')
    {

		//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('translations', 'bundles/cms/js/sections/translations_list.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.translations', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$langs = CmsPage::select_lang_translation();
		$my_lang = LANG;
		array_shift($langs);

		//GET DATA
		$data = CmsTranslation::where('lang_from', '=', $my_lang)
		->where('lang_to', '=', $lang_to)
		->order_by('word', 'asc')
		->get();

		$this->layout->content = View::make('cms::interface.pages.translation_list')
		->with('langs', $langs)
		->with('lang_from', $my_lang)
		->with('lang_to', $lang_to)
		->with('data', $data);

    }


}
