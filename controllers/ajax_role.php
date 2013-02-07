<?php

class Cms_Ajax_Role_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	//POST SAVE ROLE
	public function post_save_role()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$role = new CmsRole();
			if( ! empty($input['role_id']))
				$role = CmsRole::find($input['role_id']);

			//VALIDATION CHECK

			$rules = array(
				'role_name'  => 'required|between:2,20|unique:roles,name,'.$input['role_id'],
				'role_level' => 'not_in:0',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique' => LL('cms::validation.unique', CMSLANG)->get(),
				'not_in' => LL('cms::validation.not_in', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK

			$role->name = $input['role_name'];
			$role->level = $input['role_level'];

			$role->save();

			$rid = $role->id;

			//UPDATE ROLE LEVEL IN PAGE
			CmsPage::update_role_level($rid, $input['role_level']);

			$response = 'success';
			$msg = LL('cms::ajax_resp.role_save_success', CMSLANG)->get();

			$backurl = $input['back_url'];

		} else {

			$rid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.role_save_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'role_id',
			'id' => $rid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}

}
