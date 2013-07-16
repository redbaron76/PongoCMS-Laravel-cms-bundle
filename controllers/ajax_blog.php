<?php

class Cms_Ajax_Blog_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	//POST SETTINGS PAGE
	public function post_save_post()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$blog = new CmsBlog();
			if( ! empty($input['blog_id'])) {
				$blog = CmsBlog::find($input['blog_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			//VALIDATION CHECK

			$rules = array(
				'blog_name'  => 'required|between:2,90|unique_lang:'.$input['blog_id'].','.$input['blog_lang'].',blogs,name',
				'blog_parent' => 'not_in:0',
				'blog_slug' => 'required|alpha_slug|unique_slug:blogs,'.$input['blog_lang'].',,'.$input['blog_id'],
				'blog_date_on' => 'required|valid_datetime',
				'blog_date_off' => 'valid_datetime',
				'blog_zone' => 'not_in:0',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique_lang' => LL('cms::validation.unique', CMSLANG)->get(),
				'unique_slug' => LL('cms::validation.unique_slug', CMSLANG)->get(),
				'alpha_slug' => LL('cms::validation.alpha_slug', CMSLANG)->get(),
				'valid_datetime' => LL('cms::validation.valid_datetime', CMSLANG)->get(),
				'not_in' => LL('cms::validation.not_in', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK			

			$blog->author_id = AUTHORID;

			$blog->lang = $input['blog_lang'];
			$blog->name = $input['blog_name'];

			$slug = '/'.$input['blog_slug'];

			$parent_slug = (empty($input['blog_parent_slug'])) ? '/' : str_replace ('//', '/', $input['blog_parent_slug']);

			$blog->slug = $slug;

			//SET PERMISSION LIKE PARENT PAGE
			$page = CmsPage::find($input['blog_parent']);
        	$blog->role_id = $page->role_id;        	
        	$blog->role_level = $page->role_level;

        	$blog->text = PRETEXT($input['blog_text']);

        	$blog->datetime_on = dateTime2Db($input['blog_date_on']);

        	if(Input::has('blog_date_off')) {
        		$blog->datetime_off = dateTime2Db($input['blog_date_off']);
        	} else {
        		$blog->datetime_off = dateTimeFuture(dateTime2Db($input['blog_date_on']), 'P2Y');
        	}

        	$blog->zone = $input['blog_zone'];

        	$blog->is_valid = Input::has('is_valid') ? 1 : 0;

			$blog->save();

			$bid = $blog->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.blog_post_success', CMSLANG)->get();

			$backurl = $input['back_url'];
			$full_slug = str_replace ('//', '/', $parent_slug.$slug);

			$pid = $input['blog_parent'];

			$now = date('Y-m-d H:i:s');				

			//SYNC PIVOT TABLE

			//NEW BLOG
			if(!Input::has('blog_id')) {

				//INSERT TO PIVOT
				$blog->pages()->attach($pid, array('is_default' => 1));

			//UPDATE BLOG
			} else {

				//SET PAGE_ID WHERE IS_DEFAULT = 1
				DB::table('blogs_pages')->where_cmsblog_id($bid)->where_is_default(1)->update(array('cmspage_id' => $pid));

			}


		} else {

			$bid = null;

			$pid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.blog_post_error', CMSLANG)->get();
			$backurl = '#';
			$full_slug = '';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'blog_id',
			'id' => $bid,
			'pageid' => $pid,
			'full_slug' => $full_slug,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}

	public function post_save_preview()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$blog = new CmsBlog();
			if( ! empty($input['blog_id'])) {
				$blog = CmsBlog::find($input['blog_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$blog->author_id = AUTHORID;

			$blog->preview = PRETEXT($input['blog_preview']);

			$blog->save();

			$bid = $blog->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.page_preview_success', CMSLANG)->get();
			$backurl = $input['back_url'];

			$pid = $input['page_id'];

		} else {

			$bid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_preview_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'blog_id',
			'id' => $bid,
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
			$blog = new CmsBlog();
			if( ! empty($input['blog_id'])) {
				$blog = CmsBlog::find($input['blog_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			//VALIDATION CHECK

			$rules = array(
				'blog_title'  => 'required|max:70',
				'blog_descr'  => 'max:150',
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

			$blog->author_id = AUTHORID;

			$blog->title = $input['blog_title'];
			$blog->keyw = $input['blog_keyw'];
			$blog->descr = $input['blog_descr'];

			$blog->save();

			$bid = $blog->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.page_seo_success', CMSLANG)->get();
			$backurl = $input['back_url'];

			$pid = $input['page_id'];

		} else {

			$bid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.page_seo_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'blog_id',
			'id' => $bid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}

	public function post_save_available()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$blog = new CmsBlog();
			if( ! empty($input['blog_id'])) {
				$blog = CmsBlog::find($input['blog_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$bid = Input::get('blog_id');
			$pid = Input::get('page_id');

			if(Input::get('rel_id') !== '') {

				$rels = Input::get('rel_id');

				$now = date('Y-m-d H:i:s');				

				if(is_array($rels)) {

					$blog->pages()->sync($rels);

					//SET EXTRA_ID
					/*foreach ($rels as $value) {
						DB::table('pages')->where_id($value)->update(array('extra_id' => setExtra('blogs')));
					}*/

				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.blog_available_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			} else {

				//DELETE ALL BLOG_ID NOT DEFAULT
				DB::table('pages_pages')->where_cmsblog_id($bid)->where_is_default(0)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.blog_available_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			}

		} else {

			$bid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.blog_available_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'blog_id',
			'id' => $bid,
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
			$blog = new CmsBlog();
			if( ! empty($input['blog_id'])) {
				$blog = CmsBlog::find($input['blog_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$bid = Input::get('blog_id');
			$pid = Input::get('page_id');

			if(Input::get('rel_id') !== '') {

				$rels = Input::get('rel_id');

				$now = date('Y-m-d H:i:s');				

				if(is_array($rels)) {

					foreach ($rels as $rid) {

						$check = DB::table('blogs_blogs')->where_cmsblogrel_id($bid)->where_cmsblog_id($rid)->first();

						//D($check);

						if(empty($check)) {
							
							//ATTACH REL
							$blog->blogrels()->attach($rid);

							$reverse = array(
								'cmsblog_id' => $bid,
								'cmsblogrel_id' => $rid,
								'created_at' => $now,
								'updated_at' => $now,
							);

							//CREATE REVERSE REL
							DB::table('blogs_blogs')->insert($reverse);
						}

					}

					//DELETE NOT IN
					DB::table('blogs_blogs')->where_cmsblogrel_id($bid)->where_not_in('cmsblog_id', $rels)->delete();
					//DELETE REVERSE
					DB::table('blogs_blogs')->where_cmsblog_id($bid)->where_not_in('cmsblogrel_id', $rels)->delete();
				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.blog_relation_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			} else {

				//DELETE ALL PAGE_ID
				DB::table('blogs_blogs')->where_cmsblogrel_id($bid)->delete();
				DB::table('blogs_blogs')->where_cmsblog_id($bid)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.blog_relation_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			}

		} else {

			$bid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.blog_relation_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'blog_id',
			'id' => $bid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}


	public function post_save_tags()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$blog = new CmsBlog();
			if( ! empty($input['blog_id'])) {
				$blog = CmsBlog::find($input['blog_id']);

				//CHECK OWNERSHIP
				if(CmsRole::role_fail($input['page_id'])) {
					$msg = array('noaccess' => LL('cms::ajax_resp.ownership_error', CMSLANG)->get());
					return json_encode($msg);
				}

			}

			$bid = Input::get('blog_id');
			$pid = Input::get('page_id');

			if(Input::get('as_values_tags_id') !== '') {

				$tags = substr(Input::get('as_values_tags_id'), 0, -1);
				if(substr($tags, 0, 1) == ',') $tags = substr($tags, 1);

				$rels = explode(',', $tags);

				if(is_array($rels)) {

					$blog->tags()->sync($rels);

				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.blog_tags_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			} else {

				$response = 'success';
				$msg = LL('cms::ajax_resp.blog_tags_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			}

		} else {

			$bid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.blog_tags_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'blog_id',
			'id' => $bid,
			'pageid' => $pid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}



}
