<?php

class Cms_Page_Controller extends Cms_Base_Controller {

	//FILTERS
	public function __construct()
	{
		parent::__construct();

		//Must be logged
		$this->filter('before', 'cms_no_auth');
	}

    //PAGE LIST
    public function get_index($lang = LANG)
    {
    	
    	//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');
		
    	//LOAD JS LIBS
		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/pages_list.js', 'cms');

		//SET ACTIVE LANG
		Session::put('LANG', $lang);

		$this->layout->header_data = array(
			'title' => LL('cms::title.pages', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET PAGE DATA
		$data = CmsPage::where_lang($lang)
				->where_parent_id(0)
				->order_by('is_home', 'desc')
				->order_by('order_id', 'asc')
				->order_by('id', 'asc')
				->get();

		$new_data = array();

		foreach ($data as $obj) {
			$new_data[$obj->id] = $obj;
			$recursive = call_user_func_array('CmsPage::recursive_pages', array($obj->id));
			$new_data = ($new_data + $recursive);
		}		

		$this->layout->content = View::make('cms::interface.pages.page_list')
		->with('data', $new_data)
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

		//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/pages_edit.js', 'cms');

		//SET ACTIVE LANG
		Session::put('LANG', $lang);

		$this->layout->header_data = array(
			'title' => LL('cms::title.page_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		$data = array(
			'lang' => $lang
		);

		// LOAD LAYOUT PREVIEW
		$preview_layout = CmsPage::preview_layout_create('default');

		$this->layout->content = View::make('cms::interface.pages.page_new_edit')
		->with('role_fail', false)
		->with('title', LL('cms::title.page_new', CMSLANG))
		->with('page_id', '')
		->with('page_lang', $lang)
		->with('page_name', '')
		->with('page_parent', CmsPage::select_top_slug($lang))
		->with('page_parent_selected', 0)
		->with('page_slug', '')
		->with('page_parent_slug', '')
		->with('page_owner', CmsRole::select_edit_owners())
		->with('page_owner_selected', false)
		->with('page_access', CmsRole::select_edit_access())
		->with('page_access_selected', false)
		->with('page_extra', CmsPage::select_extra_id())
		->with('page_extra_selected', false)
		->with('page_is_home', false)
		->with('page_is_valid', true)
		->with('page_template', Config::get('cms::theme.template'))
		->with('page_template_selected', false)
		->with('page_header', Config::get('cms::theme.header'))
		->with('page_header_selected', 'default')
		->with('page_layout', Config::get('cms::theme.layout'))
		->with('page_layout_selected', 'default')
		->with('page_footer', Config::get('cms::theme.footer'))
		->with('page_footer_selected', 'default')
		->with('page_layout_preview', $preview_layout)
		->with('page_title', '')
		->with('page_preview', '')
		->with('page_keyw', '')
		->with('page_descr', '')
		->with('subpages', null)
		->with('files', array())
		->with('pagedata', array())
		->with('pagerels', array());


	}

	//EDIT PAGE FORM
	public function get_edit($id)
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

		//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		Asset::container('footer')->add('pages', 'bundles/cms/js/sections/pages_edit.js', 'cms');

		$this->layout->header_data = array(
			'title' => LL('cms::title.page_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		if(!empty($id)){
			
			//GET SUBPAGES DATA
			$subpages = CmsPage::where_parent_id($id)->order_by('order_id', 'asc')->get();

			//GET PAGE DATA
			$page = CmsPage::with(array('elements', 'files', 'pagerels'))->find($id); 
			
			if(!empty($page)) {

				$arr_slugs = explode('/', $page->slug);
				$slug = '/'.end($arr_slugs);

				//GET PAGE DATA
				$pagedata = CmsPage::where_lang($page->lang)
						->where('id', '<>', $id)
						->where_parent_id(0)
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

				if(empty($new_data)) $new_data = array();

				// LOAD LAYOUT PREVIEW
				$preview_layout = CmsPage::preview_layout_create($page->layout);
		
				$this->layout->content = View::make('cms::interface.pages.page_new_edit')
				->with('role_fail', CmsRole::role_fail($id))
				->with('title', LL('cms::title.page_edit', CMSLANG))
				->with('page_id', $id)
				->with('page_lang', $page->lang)
				->with('page_name', $page->name)
				->with('page_parent', CmsPage::select_top_slug($page->lang, $id))
				->with('page_parent_selected', $page->parent_id)
				->with('page_slug', str_replace('/', '', $slug))
				->with('page_parent_slug', str_replace($slug, '', $page->slug))
				->with('page_owner', CmsRole::select_edit_owners())
				->with('page_owner_selected', $page->role_id)
				->with('page_access', CmsRole::select_edit_access())
				->with('page_access_selected', $page->access_level)
				->with('page_extra', CmsPage::select_extra_id())
				->with('page_extra_selected', $page->extra_id)
				->with('page_is_home', (bool) $page->is_home)
				->with('page_is_valid', (bool) $page->is_valid)
				->with('page_template', Config::get('cms::theme.template'))
				->with('page_template_selected', $page->template)
				->with('page_header', Config::get('cms::theme.header'))
				->with('page_header_selected', $page->header)
				->with('page_layout', Config::get('cms::theme.layout'))
				->with('page_layout_selected', $page->layout)
				->with('page_footer', Config::get('cms::theme.footer'))
				->with('page_footer_selected', $page->footer)
				->with('page_layout_preview', $preview_layout)
				->with('page_title', $page->title)
				->with('page_preview', $page->preview)
				->with('page_keyw', $page->keyw)
				->with('page_descr', $page->descr)
				->with('subpages', $subpages)
				->with('files', $page->files)
				->with('pagedata', $new_data)
				->with('pagerels', $page->pagerels)
				->with('elements', $page->elements);

			} else {

				$this->layout->content = View::make('cms::interface.pages.not_found')
									 ->with('message', LL('cms::alert.not_found', CMSLANG));

			}

		} else {

			$this->layout->content = View::make('cms::interface.pages.not_found')
									 ->with('message', LL('cms::alert.not_found', CMSLANG));

		}

	}

	//POST DELETE PAGE
	public function post_delete_page()
	{
		if(Input::has('page_id')) {

			$pid = Input::get('page_id');

			$elements = CmsPage::find($pid)->elements;

			//CHECK IF CONTAINS ELEMENTS

			if(!empty($elements) and !Input::has('force_delete')) {

				Notification::error(LL('cms::alert.delete_page_stillelements_error', CMSLANG), 2500);

				return Redirect::to_action('cms::page', array(LANG));		

			} else {

				//CHECK IF CONTAINS SUBPAGES

				$subpages = CmsPage::where_parent_id($pid)->first();

				if(!empty($subpages)) {

					Notification::error(LL('cms::alert.delete_page_stillsubpages_error', CMSLANG), 2500);

					return Redirect::to_action('cms::page', array(LANG));

				} else {

					// FORCE DELETE IS FLAGGED -> DETACH ELEMENTS
					if(Input::has('force_delete')) {

						// Ciclo tutti gli elementi collegati alla pagina
						foreach ($elements as $element) {
							
							// Elimino il link dell'elemento con la pagina
							DB::table('elements_pages')
								->where_cmspage_id($pid)
								->where_cmselement_id($element->id)
								->delete();

							// Conto quante altre pagine hanno l'elemento
							$n = DB::table('elements_pages')
								->where_cmselement_id($element->id)
								->count();

							// Se ritorna 0, elimino
							if($n == 0) CmsElement::find($element->id)->delete();

						}

					}

					$page = CmsPage::find($pid);

					$lang = $page->lang;

					//DELETE FILES ASSOCIATION
					$page->files()->delete();

					//DELETE BLOG ASSOCIATIONS
					$page->blogs()->delete();

					//DELETE PAGE RELATIONS
					$page->pagerels()->delete();

					//DELETE MENU RELATIONS
					$page->menus()->delete();

					//DELETE PAGE
					$page->delete();

					Notification::success(LL('cms::alert.delete_page_success', CMSLANG, array('page' => $page->name)), 1500);

					return Redirect::to_action('cms::page', array($lang));

				}

			}

		} else {

			Notification::error(LL('cms::alert.delete_page_error', CMSLANG), 1500);

			return Redirect::to_action('cms::page', array(LANG));
		}
	}


	//NEW ELEMENT FORM
    public function get_new_element($page_id)
	{

		//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('count', 'bundles/cms/js/jquery.charcount.js', 'jquery');
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

		//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		Asset::container('footer')->add('elements', 'bundles/cms/js/sections/elements_edit.js', 'cms');


		$this->layout->header_data = array(
			'title' => LL('cms::title.element_new', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		//GET PAGE DATA
		$page = CmsPage::find($page_id);

		//GET ELEMENTS DATA
		$elements = $page->elements;

		//GET FILE DATA
		$files = $page->files;

		// LOAD LAYOUT PREVIEW
		$preview_layout = CmsPage::preview_layout_create($page->layout);

		$this->layout->content = View::make('cms::interface.pages.element_new_edit')
		->with('role_fail', CmsRole::role_fail($page_id))
		->with('title', LL('cms::title.element_new', CMSLANG))
		->with('page_id', $page_id)
		->with('page_name', $page->name)
		->with('element_id', '')
		->with('element_name', '')
		->with('element_label', '')
		->with('element_text', '')
		->with('element_zones', CmsElement::select_zone($page_id))
		->with('element_zone_selected', true)
		->with('element_is_valid', true)
		->with('elements', $elements)
		->with('media', $files)

		->with('page_header_selected', $page->header)
		->with('page_footer_selected', $page->footer)
		->with('page_layout_preview', $preview_layout);
		
	}


	//EDIT ELEMENT FORM
    public function get_edit_element($page_id, $element_id)
	{
		
		//LOAD JS LIBS
		Asset::container('footer')->add('form', 'bundles/cms/js/jquery.form.js', 'jquery');
		Asset::container('footer')->add('count', 'bundles/cms/js/jquery.charcount.js', 'jquery');
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

		//SORTING
		Asset::container('footer')->add('sortable', 'bundles/cms/js/jquery.sortable.js', 'jquery');
		Asset::container('footer')->add('serialize', 'bundles/cms/js/jquery.serializetree.js', 'sortable');

		Asset::container('footer')->add('elements', 'bundles/cms/js/sections/elements_edit.js', 'cms');


		$this->layout->header_data = array(
			'title' => LL('cms::title.element_edit', CMSLANG)
		);

		$this->layout->top_data = array(
			'search' => false
		);

		if(!empty($element_id)){

			//GET ELEMENT DATA
			$element = CmsElement::find($element_id);

			if(!empty($element)) {

				//GET PAGE DATA
				$page = CmsPage::find($page_id);

				//GET ELEMENTS DATA
				$elements = $page->elements;

				//GET FILE DATA
				$files = $page->files;

				// LOAD LAYOUT PREVIEW
				$preview_layout = CmsPage::preview_layout_create($page->layout);

				$this->layout->content = View::make('cms::interface.pages.element_new_edit')
				->with('role_fail', CmsRole::role_fail($page_id))
				->with('title', LL('cms::title.element_edit', CMSLANG))
				->with('page_id', $page_id)
				->with('page_name', $page->name)
				->with('element_id', $element_id)
				->with('element_name', $element->name)
				->with('element_label', $element->label)
				->with('element_text', DECODETEXT($element->text))
				->with('element_zones', CmsElement::select_zone($page_id))
				->with('element_zone_selected', $element->zone)
				->with('element_is_valid', (bool) $element->is_valid)
				->with('elements', $elements)
				->with('media', $files)

				->with('page_header_selected', $page->header)
				->with('page_footer_selected', $page->footer)
				->with('page_layout_preview', $preview_layout);

			} else {

				$this->layout->content = View::make('cms::interface.pages.not_found')
						 					->with('message', LL('cms::alert.not_found', CMSLANG));

			}

		} else {

			$this->layout->content = View::make('cms::interface.pages.not_found')
									 	->with('message', LL('cms::alert.not_found', CMSLANG));

		}

	}


	//DELETE ELEMENT
    public function post_delete_element()
	{
		
		if(Input::has('page_id') and Input::has('element_id')) {

			$pid = Input::get('page_id');
			$eid = Input::get('element_id');

			//GET ELEMENT MODEL
			$element = CmsElement::find($eid);

			$lang = $element->lang;

			//ACCESS PIVOT TABLE
			$pivot = $element->pages()->pivot();

			//COUNT ELEMENT LINKS
			$n = $pivot->count();

			//DELETE LINK
			$pivot->where_cmspage_id($pid)->delete();

			//IF JUST 1 ELEMENT, DELETE PERMANENTLY
			if($n == 1) $element->delete();

			Notification::success(LL('cms::alert.delete_element_success', CMSLANG, array('element' => $element->name)), 1500);

			return Redirect::to_action('cms::page', array($lang));

		} else {

			Notification::error(LL('cms::alert.delete_element_error', CMSLANG), 1500);

			return Redirect::to_action('cms::page', array(LANG));

		}
	}


	//CLONE PAGE
	public function post_clone_page()
	{
		if(Input::has('page_id')) {

			$pid = Input::get('page_id');
			$lang = Input::get('lang');
			$now = date('Y-m-d H:i:s');

			//SET ACTIVE LANG
			Session::put('LANG', $lang);			

			//GET PAGE DATA
			$page = CmsPage::find($pid);

			//SET PARENT 0 IF CHANGE LANG
			if($lang == LANG) {
				$parent_id = $page->parent_id;
				$slug = $page->slug;
			} else {
				$parent_id = 0;
				$slug = '/'.Str::slug($page->name, '-');
			}

			$new_page_attr = array(
				'parent_id' => $parent_id,
				'role_id' => $page->role_id,
				'role_level' => $page->role_level,
				'author_id' => AUTHORID,
				'slug' => $slug,
				'name' => $page->name,
				'title'=> $page->title,
				'keyw' => $page->keyw,
				'descr' => $page->descr,
				'header' => $page->header,
				'layout' => $page->layout,
				'footer' => $page->footer,
				'access_level' => $page->access_level,
				'extra_id' => $page->extra_id,
				'order_id' => $page->order_id,
				'lang' => $lang,
				'is_home' => $page->is_home,
				'is_valid' => 0
			);

			//WRITE NEW PAGE
			$new_page = new CmsPage($new_page_attr);
			$new_page->save();

			// KEEP OPEN
			Session::flash('keep_open_item', array(
					'parent_id' => $parent_id,
					'page_id' => $new_page->id)
			);

			if(Input::has('clone_media') and Input::get('clone_media')==1) {

				//GET NEW ID
				$nid = $new_page->id;

				//GET ALL MEDIA IN PIVOT WHERE OLD PAGE_ID
				// $pivot = $page->files()->pivot();

				foreach (DB::table('files_pages')->where_cmspage_id($pid)->get() as $value) {

					$clone_array = array(
						'cmsfile_id' => $value->cmsfile_id,
						'cmspage_id' => $nid,
						'created_at' => $now,
						'updated_at' => $now,
					);

					DB::table('files_pages')->insert($clone_array);

				}

			}

			// IF IS ARRAY CLONE_ELEMENTS

			if(is_array(Input::get('clone_elements'))) {

				//GET NEW PAGE ID
				$nid = $new_page->id;

				$elements_to_be_cloned = Input::get('clone_elements');

				// FOR EACH ELEMENT TO CLONE
				foreach ($elements_to_be_cloned as $el) {

					//GET ORIGINAL ORDER_ID
					$original_el = DB::table('elements_pages')
									->where_cmspage_id($pid)
									->where_cmselement_id($el)
									->first();

					$ororder = (!empty($original_el)) ? $original_el->order_id : Config::get('cms::settings.order');
					
					// LANG CHANGES
					if($lang != LANG) {

						// ALWAYS SEPARATE ELEMENTS

						$element = CmsElement::find($el);

						$new_element_attr = array(
							'author_id' => AUTHORID,
							'name' => $element->name,
							'label' => $element->label,
							'text' => $element->text,
							'zone' => $element->zone,
							'lang' => $lang,
							'is_valid' => 0
						);

						$new_element = new CmsElement($new_element_attr);
						$page = CmsPage::find($nid);
						$page->elements()->insert($new_element, array('order_id' => $ororder));

					} else {

						// CHECK IF ELEMENTS SEPARATE OR NOT

						$separate_elements = Input::get('ele_separate');

						if(is_array($separate_elements) and in_array($el, $separate_elements)) {

							// ELEMENT MUST BE SEPARATED

							$element = CmsElement::find($el);

							$new_element_attr = array(
								'author_id' => AUTHORID,
								'name' => $element->name,
								'label' => $element->label,
								'text' => $element->text,
								'zone' => $element->zone,
								'lang' => $lang,
								'is_valid' => 0
							);

							$new_element = new CmsElement($new_element_attr);
							$page = CmsPage::find($nid);
							$page->elements()->insert($new_element, array('order_id' => $ororder));

						} else {

							//CLONE ELEMENT

							$clone_array = array(
								'cmselement_id' => $el,
								'cmspage_id' 	=> $nid,
								'order_id' 		=> $ororder,
								'created_at' 	=> $now,
								'updated_at' 	=> $now,
							);

							DB::table('elements_pages')->insert($clone_array);

						}

					}

				}

			}

			Notification::success(LL('cms::alert.clone_page_success', CMSLANG, array('element' => $page->name)), 1500);

			return Redirect::to_action('cms::page', array($lang));


		} else {

			Notification::error(LL('cms::alert.clone_page_error', CMSLANG), 1500);

			return Redirect::to_action('cms::page', array($lang));
		}


	}

	//CLONE ELEMENT
	public function post_clone_element()
	{

		if(Input::has('page_id') and Input::has('element_id') and Input::has('newpage_id')) {

			$pid = Input::get('page_id');
			$nid = Input::get('newpage_id');
			$eid = Input::get('element_id');

			$now = date('Y-m-d H:i:s');

			if(Input::has('to_clone')) {

				//CREATE NEW ELEMENT

				//GET ELEMENT MODEL
				$element = CmsElement::find($eid);				

				$new_element_attr = array(
					'author_id' => AUTHORID,
					'name' => $element->name,
					'label' => $element->label,
					'text' => $element->text,
					'zone' => $element->zone,
					'lang' => LANG,
					'is_valid' => 0
				);

				$new_element = new CmsElement($new_element_attr);
				$page = CmsPage::find($nid);
				$page->elements()->insert($new_element);


			} else {

				//GET ELEMENT MODEL
				$element = CmsElement::find($eid);

				$clone_array = array(
					'cmselement_id' => $eid,
					'cmspage_id' => $nid,
					'created_at' => $now,
					'updated_at' => $now,
				);

				DB::table('elements_pages')->insert($clone_array);

			}

			Notification::success(LL('cms::alert.clone_element_success', CMSLANG, array('element' => $element->name)), 1500);

			return Redirect::to_action('cms::page', array(LANG));

		} else {

			Notification::error(LL('cms::alert.clone_element_error', CMSLANG), 1500);

			return Redirect::to_action('cms::page', array(LANG));

		}


	}



	//GET PAGE POPOVER DETAILS
	public function post_popover_details()
	{
		$auth = Auth::check();
		
		if($auth and is_numeric(AUTHORID)) {

			if(Input::has('id')) {

				$pid = Input::get('id');

				//CACHE DATA
				if(CACHE) {
					$page = Cache::remember('page_'.$pid.'_details', function() use ($pid) {
						return CmsPage::with(array('user', 'pagerels', 'elements'))->find($pid);
					}, 60);
				} else {
					$page = CmsPage::with(array('user', 'pagerels', 'elements'))->find($pid);
				}

				return View::make('cms::interface.partials.page_details')
				->with('author', $page->user->username)
				->with('created_at', $page->created_date)
				->with('updated_at', $page->updated_date)
				->with('header', $page->header)
				->with('layout', $page->layout)
				->with('footer', $page->footer)
				->with('is_valid', (bool) $page->is_valid)
				->with('lang', $page->lang)
				->with('elements', $page->elements)
				->with('pagerels', $page->pagerels);

			}

		}
		
	}	


}
