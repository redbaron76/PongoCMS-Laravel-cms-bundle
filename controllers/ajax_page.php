<?php

class Cms_Ajax_Page_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	//GET SLUG PARENT PATHS VIA AJAX ON_CHANGE PAGE SETTINGS POSITION
	public function post_parent_paths()
	{
		$auth = Auth::check();
		
		if($auth and Input::has('parent_id')) {

			$parent_id = Input::get('parent_id');
			
			if($parent_id == 0) return '';
			
			$page = CmsPage::find($parent_id);
			
			return $page->slug;

		}
	}


	//GET ZONES FOR PAGE LAYOUT
	public function post_parent_zones()
	{
		$auth = Auth::check();
		
		if($auth and Input::has('parent_id')) {

			$parent_id = Input::get('parent_id');
			
			if($parent_id == 0) return '';
			
			$page = CmsPage::find($parent_id);
			
			$layout = ( ! empty($page->layout)) ? $page->layout : 'default';

			return Response::json(Config::get('cms::theme.layout_' . $layout));

		}
	}


	//POST SETTINGS PAGE
	public function post_save_settings()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$page = new CmsPage();
			if( ! empty($input['page_id'])) {
				$page = CmsPage::find($input['page_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			//VALIDATION CHECK

			$rules = array(
				'page_name'  => 'required|between:3,30|unique_lang:'.$input['page_id'].','.$input['page_lang'].',pages,name',
				'page_slug' => 'required|alpha_slug|unique_slug:pages,'.$input['page_lang'].','.$input['page_parent_slug'].','.$input['page_id'],
				'page_owner' => 'not_in:0',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique_lang' => LL('cms::validation.unique', CMSLANG)->get(),
				'unique_slug' => LL('cms::validation.unique_slug', CMSLANG)->get(),
				'alpha_slug' => LL('cms::validation.alpha_slug', CMSLANG)->get(),
				'not_in' => LL('cms::validation.not_in', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK			

			$page->author_id = AUTHORID;

			$page->lang = $input['page_lang'];
			$page->name = $input['page_name'];
			$page->parent_id = $input['page_parent'];

			$slug = (empty($input['page_parent_slug']) and empty($input['page_slug'])) ? '/' : str_replace ('//', '/', $input['page_parent_slug'].'/'.$input['page_slug']);

			$page->slug = $slug;

			//UPDATE ALL CHILD SLUGS
			CmsPage::update_child_slugs($input['page_id'], $input['page_parent_slug'], $input['page_slug']);

        	$page->role_id = $input['page_owner'];

        	$role = CmsRole::find($input['page_owner']);
        	$page->role_level = $role->level;
        	
        	$page->access_level = $input['page_access'];
        	$page->extra_id = $input['page_extra'];
        	
        	if(empty($input['page_id'])) {
				$page->order_id = Config::get('cms::settings.order');
			}

        	$page->is_home = Input::has('is_home') ? 1 : 0;
        	$page->is_valid = Input::has('is_valid') ? 1 : 0;

        	//IF NEW PAGE, SAVE DEFAULT LAYOUT

        	if(empty($input['page_id'])) {
        		$page->template = 'default';
	        	$page->header = 'default';
				$page->layout = 'default';
				$page->footer = 'default';
			}

			$page->save();

			$pid = $page->id;

			// KEEP OPEN
			Session::flash('keep_open_item', array(
					'parent_id' => $input['page_parent'],
					'page_id' => $pid)
			);

			if(CACHE) Cache::forget('page_'.$pid.'_details');

			//CHECK IS_HOME ALREADY PRESENT > DISABLE
			if(Input::has('is_home')) {
				DB::table('pages')->where('id', '<>', $pid)
									->where_is_home(1)
									->where_lang($input['page_lang'])
									->update(array('is_home' => 0));
			}

			$response = 'success';
			$msg = LL('cms::ajax_resp.page_settings_success', CMSLANG)->get();

			$backurl = $input['back_url'];
			$full_slug = URL::base().$slug;

		} else {

			$pid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_settings_error', CMSLANG)->get();
			$backurl = '#';
			$full_slug = '';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'page_id',
			'id' => $pid,
			'pageid' => $pid,
			'full_slug' => $full_slug,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}

	public function post_save_design()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$page = new CmsPage();
			if( ! empty($input['page_id'])) {
				$page = CmsPage::find($input['page_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$page->author_id = AUTHORID;

			$page->template = $input['page_template'];
			$page->header = $input['page_header'];
			$page->layout = $input['page_layout'];
			$page->footer = $input['page_footer'];

			$page->save();

			$pid = $page->id;

			if(CACHE) Cache::forget('page_'.$pid.'_details');

			$pid = $page->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.page_design_success', CMSLANG)->get();
			$backurl = $input['back_url'];

		} else {
			
			$pid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_design_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'page_id',
			'id' => $pid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}

	public function post_save_seo()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$page = new CmsPage();
			if( ! empty($input['page_id'])) {
				$page = CmsPage::find($input['page_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			//VALIDATION CHECK

			$rules = array(
				'page_title'  => 'max:70',
				'page_descr'  => 'max:150',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'max' => LL('cms::validation.max.string', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			$page->author_id = AUTHORID;

			$page->title = $input['page_title'];
			$page->keyw = $input['page_keyw'];
			$page->descr = $input['page_descr'];

			$page->save();

			$pid = $page->id;

			if(CACHE) Cache::forget('page_'.$pid.'_details');

			$response = 'success';
			$msg = LL('cms::ajax_resp.page_seo_success', CMSLANG)->get();
			$backurl = $input['back_url'];

		} else {

			$pid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_seo_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'page_id',
			'id' => $pid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}

	public function post_upload_media()
	{
		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::all();

			$file_name = strtolower(preg_replace('/[^\w\._]+/', '_', $input['file']['name']));
			$file_name = str_replace('.jpeg','.jpg',$file_name);
			$file_size = $input['file']['size'];
			$file_ext = strtolower(File::extension($file_name));
			$page_id = $input['page_id'];
			$file_w = 0;
			$file_h = 0;

			//CREATE UPLOAD PATH

			$img_mimes = array('jpg', 'jpeg', 'gif', 'png');

			if (in_array($file_ext, $img_mimes)) {

				$up = 'img/';

				$is_image = true;

			} else {

				$up = $file_ext . '/';

				$is_image = false;

			}

			//BUILD UPLOAD PATH
			$upload_path = Config::get('cms::settings.data') . $up;

			//VALIDATION CHECK
			
			$get_mimes = str_replace(' ', '', Config::get('cms::settings.mimes'));
			$get_max = Config::get('cms::settings.max_size') * 1024000;	//1Mb

			// CHECK SIZE EXCEEDS
			
			$exceeds = (!$is_image and $file_size > $get_max) ? true : false;

			// CHECK SIZE IF IMAGE
			if($is_image) {

				$rules = array(
					'page_id'  => 'required',
					'file'  => 'mimes:'.$get_mimes.'|max:'.$get_max.'|unique_file:'.$file_name.','.$upload_path,
				);

			} else {

				$rules = array(
					'page_id'  => 'required',
					'file'  => 'mimes:'.$get_mimes.'|unique_file:'.$file_name.','.$upload_path,
				);

			}			

			$messages = array(
				'required' => LL('cms::validation.page_not_set', CMSLANG)->get(),
				'mimes' => LL('cms::validation.mimes_not_valid', CMSLANG)->get(),
				'max' => LL('cms::validation.max_file_size', CMSLANG)->get(),
				'unique_file' => LL('cms::validation.unique_file', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails()) {

				//ERROR RESPONSE
				return json_encode(array('type' => 'label-important', 'message' => $validation->errors->first()));

			} else {

				//CREATE DIR IF NOT EXISTS
				if(!file_exists(path('public').$upload_path)) mkdir(path('public').$upload_path);

				$path = strtolower($upload_path . $file_name);

				// DO UPLOAD ONLY IF IS IMAGE OR LOWER THAN MAX SIZE ALLOWED
				$status_upload = (!$exceeds) ? move_uploaded_file($input['file']['tmp_name'], path('public').$path) : true;

				// UPLOAD OK
				if($status_upload) {

					//SET ICO PATH
					$thumb_path = '/bundles/cms/img/'.$file_ext.'_ico.png';

					//GET IMG DIMENSIONS
					if($is_image) {

						$img = getimagesize(path('public').$path);
						$file_w = $img[0];
						$file_h = $img[1];

						//GENERATE THUMBS
						$thumb_path = CmsFile::create_thumb($upload_path, $file_name, $file_ext);

					}

					//SAVE TO DB

					$file_attr = array(
						'name' => $file_name,
						'ext' => $file_ext,
						'size' => $file_size,
						'w' => $file_w,
						'h' => $file_h,
						'path' => '/'.$path,
						'thumb' => $thumb_path,
						'is_image' => ($is_image) ? 1 : 0,
						'is_valid' => 1
					);

					$file = new CmsFile($file_attr);
					$page = CmsPage::find($page_id);
					$page->files()->insert($file);

					// SUCCESS RESPONSE
					$resp = array(
						'type' => ($exceeds) ? 'label-info' : 'label-success',
						'message' => ($exceeds) ? LL('cms::ajax_resp.page_upload_ftp', CMSLANG, array('path' => $upload_path))->get() : LL('cms::ajax_resp.page_upload_success', CMSLANG)->get(),
						'name' => $file_name,
						'path' => '/'.$path,
						'thumb_path' => $thumb_path,
						'is_img' => ($is_image) ? true : false
					);

					return json_encode($resp);

				} else {

					// ERROR RESPONSE
					$resp = array(
						'type' => 'label-important',
						'message' => LL('cms::ajax_resp.page_upload_error', CMSLANG)->get()
					);

					return json_encode($resp);

				}

			}

		}

	}


	public function post_save_preview()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$page = new CmsPage();
			if( ! empty($input['page_id'])) {
				$page = CmsPage::find($input['page_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$page->author_id = AUTHORID;

			$page->preview = PRETEXT($input['page_preview']);

			$page->save();

			$pid = $page->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.page_preview_success', CMSLANG)->get();
			$backurl = $input['back_url'];

		} else {

			$pid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_preview_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'page_id',
			'id' => $pid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}


	public function post_save_relations()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$page = new CmsPage();
			if( ! empty($input['page_id'])) {
				$page = CmsPage::find($input['page_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$pid = Input::get('page_id');

			if(Input::has('rel_id')) {

				$rels = Input::get('rel_id');

				$now = date('Y-m-d H:i:s');				

				if(is_array($rels)) {

					foreach ($rels as $rid) {

						$check = DB::table('pages_pages')->where_cmspagerel_id($pid)->where_cmspage_id($rid)->first();

						//D($check);

						if(empty($check)) {
							
							//ATTACH REL
							$page->pagerels()->attach($rid);

							$reverse = array(
								'cmspage_id' => $pid,
								'cmspagerel_id' => $rid,
								'created_at' => $now,
								'updated_at' => $now,
							);

							//CREATE REVERSE REL
							DB::table('pages_pages')->insert($reverse);
						}

					}

					//DELETE NOT IN
					DB::table('pages_pages')->where_cmspagerel_id($pid)->where_not_in('cmspage_id', $rels)->delete();
					//DELETE REVERSE
					DB::table('pages_pages')->where_cmspage_id($pid)->where_not_in('cmspagerel_id', $rels)->delete();
				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.page_relation_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			} else {

				//DELETE ALL PAGE_ID
				DB::table('pages_pages')->where_cmspagerel_id($pid)->delete();
				DB::table('pages_pages')->where_cmspage_id($pid)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.page_relation_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			}

		} else {

			$pid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_relation_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'page_id',
			'id' => $pid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}


	public function post_save_element_settings()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//OWNERSHIP
			if( ! empty($input['page_id'])) {

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			//VALIDATION CHECK

			$rules = array(
				'element_name'  => 'required|alpha_dash|unique_element_page:'.$input['page_id'].','.$input['element_id'].',name',
				'element_label' => 'required',
				'element_zone' => 'not_in:0',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'unique_element_page' => LL('cms::validation.unique_element_page', CMSLANG)->get(),
				'max' => LL('cms::validation.max.string', CMSLANG)->get(),
				'alpha_dash' => LL('cms::validation.alpha_dash', CMSLANG)->get(),
				'not_in' => LL('cms::validation.not_in', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			$element = new CmsElement();
			if( ! empty($input['element_id']))
				$element = CmsElement::find($input['element_id']);

			$element->author_id = AUTHORID;			
			$element->name = strtolower($input['element_name']);
			$element->label = $input['element_label'];
			$element->zone = $input['element_zone'];
			$element->lang = LANG;
			$element->is_valid = Input::has('is_valid') ? 1 : 0;

			$element->save();

			$eid = $element->id;

			$page_id = $input['page_id'];

			$page = CmsPage::find($page_id);

			// KEEP OPEN
			Session::flash('keep_open_item', array(
					'parent_id' => $page->parent_id,
					'page_id' => $page_id)
			);

			//IF NEW ADD TO PIVOT TABLE
			if(empty($input['element_id']))
				$page->elements()->attach($eid, array('order_id' => Config::get('cms::settings.order')));			

			$response = 'success';
			$msg = LL('cms::ajax_resp.element_success', CMSLANG)->get();
			$backurl = $input['back_url'];

			// Template returned
			$template  = '<li id="'.$page_id.'_'.$eid.'" data-zone="'.$input['element_zone'].'">';
			$template .= '<a class="btn" href="#">';
			$template .= '<i class="icon-resize-vertical"></i>';
			$template .= $input['element_label'];
			$template .= '</a>';
			$template .= '</li>';

			// Inject container
			$inject = 'ul.sortable';
			$zone = $input['element_zone'];
			$detach = false;

		} else {

			$eid = null;

			$page_id = null;			

			$response = 'error';
			$msg = LL('cms::ajax_resp.element_error', CMSLANG)->get();
			$backurl = '#';

			$template = '';
			$inject = '';
			$zone = '';
			$detach = false;

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'element_id',
			'id' => $eid,
			'pageid' => $page_id,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl,
			'detach' => $detach,
			'inject' => $inject,
			'zone' => $zone,
			'template' => $template
		);

		return json_encode($data);

	}


	public function post_save_element_text()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//OWNERSHIP
			if( ! empty($input['page_id'])) {

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$element = new CmsElement();
			if( ! empty($input['element_id']))
				$element = CmsElement::find($input['element_id']);

			$element->author_id = AUTHORID;			
			$element->text = PRETEXT($input['element_text']);
			$element->lang = LANG;

			$element->save();

			$eid = $element->id;

			$page_id = $input['page_id'];

			$page = CmsPage::find($page_id);

			//IF NEW ADD TO PIVOT TABLE
			if(empty($input['element_id']))
				$page->elements()->attach($eid);			

			$response = 'success';
			$msg = LL('cms::ajax_resp.element_success', CMSLANG)->get();
			$backurl = $input['back_url'];

		} else {

			$eid = null;

			$page_id = null;			

			$response = 'error';
			$msg = LL('cms::ajax_resp.element_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'element_id',
			'id' => $eid,
			'pageid' => $page_id,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}


	//MEDIA LIST

	public function post_media_list()
	{
		if(Input::has('pid')) {

			$page_id = Input::get('pid');

			//GET FILE DATA
			$files = CmsPage::find($page_id)->files;

		} else {
			
			$files = null;

		}

		return View::make('cms::interface.partials.media_list')
		->with('media', $files);
		
	}

	//ORDER PAGE LIST

	public function post_order_list()
	{
		
		$order = Input::get('order');
		
		if(is_array($order)) {
			
			foreach($order as $order_id => $item) {
				$order_id++;
				$p = explode("_", $item);
				$page = CmsPage::find($p[1]);
				$page->order_id = $order_id;
				$page->save();
			}
			
		}

		return true;

	}

	//ORDER SORTABLE

	public function post_order_subpage()
	{
		
		$order = Input::get('order');
		
		if(is_array($order)) {
			
			//SET 1000
			CmsPage::where_order_id(0)->update(array('order_id' => Config::get('cms::settings.order')));

			foreach($order as $order_id => $item) {
				$order_id++;
				$p = explode("_", $item);
				$page = CmsPage::find($p[1]);
				$page->order_id = $order_id;
				$page->save();
			}
			
		}

		return true;

	}

	public function post_order_element()
	{

		$order = Input::get('order');
		
		if(is_array($order)) {
			
			foreach($order as $order_id => $item) {
				$order_id++;
				$p = explode("_", $item);
				$element = CmsElement::find($p[1]);

				$order = array(
					'order_id' => $order_id
				);

				DB::table('elements_pages')
					->where_cmspage_id($p[0])
					->where_cmselement_id($p[1])
					->update($order);

			}
			
		}

		return true;

	}

	// CREATE LIVE LAYOUT PREVIEW
	public function post_preview_layout()
	{

		if(Input::has('layout')) {

			$layout = Input::get('layout');

			return CmsPage::preview_layout_create($layout);

		}

	}


}
