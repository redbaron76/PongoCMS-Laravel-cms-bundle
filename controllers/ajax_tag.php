<?php

class Cms_Ajax_Tag_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	//POST SAVE tag
	public function post_save_tag()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$tag = new CmsTag();
			if( ! empty($input['tag_id']))
				$tag = CmsTag::find($input['tag_id']);

			//VALIDATION CHECK

			$rules = array(
				'tag_name'  => 'required|between:2,20|unique_lang:'.$input['tag_id'].','.$input['tag_lang'].',tags,name',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique_lang' => LL('cms::validation.unique_lang', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK

			$tag->name = $input['tag_name'];
			$tag->lang = $input['tag_lang'];

			$tag->save();

			$tid = $tag->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.tag_save_success', CMSLANG)->get();

			$backurl = $input['back_url'];

		} else {

			$rid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.tag_save_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'tag_id',
			'id' => $tid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}


	//GET TAGS
	public function get_tags()
	{

		if(Input::has('q') and Input::has('lang')) {

			$q = Input::get('q');
			$lang = Input::get('lang');

			$tags = CmsTag::where_lang($lang)
					->where('name', 'LIKE', '%'.$q.'%')
					->get();

			$data = array();

			foreach ($tags as $tag) {
				$json = array();
				$json['value'] = $tag->id;
				$json['name'] = $tag->name;
				$data[] = $json;
			}

			header("Content-type: application/json");
			return json_encode($data);

		}

	}


	//GET TAGS
	public function post_populate_tags($what)
	{

		if(Input::has('id')) {

			$id = Input::get('id');

			$tags = CmsBlog::find($id)->tags;

			$data = array();

			foreach ($tags as $tag) {
				$json = array();
				$json['value'] = $tag->id;
				$json['name'] = $tag->name;
				$data[] = $json;
			}

			header("Content-type: application/json");
			return json_encode($data);

		}

		return false;

	}

	//POST TAGS ON THE FLY
	public function post_add_tags()
	{
		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$tag = new CmsTag();

			//VALIDATION CHECK

			$rules = array(
				'tag_name'  => 'required|between:2,20|unique_lang:,'.$input['tag_lang'].',tags,name',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique_lang' => LL('cms::validation.unique_lang', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK

			$tag->name = $input['tag_name'];
			$tag->lang = $input['tag_lang'];

			$tag->save();

			$tid = $tag->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.tag_save_success', CMSLANG)->get();

			$backurl = '#';

		} else {

			$rid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.tag_save_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'tag_id',
			'id' => $tid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}

}
