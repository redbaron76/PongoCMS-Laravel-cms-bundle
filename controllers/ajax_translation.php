<?php

class Cms_Ajax_Translation_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	//POST SAVE ROLE
	public function post_save_translation()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$trans = new CmsTranslation();
			if( ! empty($input['translation_id']))
				$trans = CmsTranslation::find($input['translation_id']);

			//VALIDATION CHECK

			$rules = array(
				'word'  => 'required',
				'value'  => 'required'
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'unique' => LL('cms::validation.unique', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK

			$trans->lang_from = $input['lang_from'];
			$trans->lang_to = $input['trans_to'];
			$trans->word = $input['word'];
			$trans->value = $input['value'];

			$trans->save();

			//DELETE CACHE
			if(CACHE) Cache::forget('trans_'.md5($input['word']).'_'.LANG);

			$tid = $trans->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.translation_save_success', CMSLANG)->get();

		} else {

			$tid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.translation_save_error', CMSLANG)->get();

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'translation_id',
			'id' => $tid,
			'response' => $response,
			'message' => $msg,
			'word' => $input['word'],
			'value' => $input['value'],
			'edit' => LL('cms::button.edit', CMSLANG)->get(),
			'delete' => LL('cms::button.delete', CMSLANG)->get()
		);

		return json_encode($data);
	}

	//POST SAVE ROLE
	public function post_delete_translation()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$id = Input::get('id');

			$trans = CmsTranslation::find($id);
			$trans->delete();

			$tid = $id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.translation_delete_success', CMSLANG)->get();

		} else {

			$tid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.translation_delete_error', CMSLANG)->get();

		}

		$data = array(
			'auth' => $auth,
			'id' => $tid,
			'response' => $response,
			'message' => $msg
		);

		return json_encode($data);
	}

}
