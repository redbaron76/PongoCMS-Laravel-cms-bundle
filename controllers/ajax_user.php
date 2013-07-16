<?php

class Cms_Ajax_User_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	//POST SAVE ACCOUNT
	public function post_save_account()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$user = new CmsUser();
			if( ! empty($input['user_id']))
				$user = CmsUser::find($input['user_id']);

			//VALIDATION CHECK

			$rules = array(
				'user_username'  => 'required|between:2,20|unique:users,username,'.$input['user_id'],
				'user_email' => 'required|email|unique:users,email,'.$input['user_id'],
				'user_role' => 'not_in:0',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique' => LL('cms::validation.unique', CMSLANG)->get(),
				'email' => LL('cms::validation.email', CMSLANG)->get(),
				'not_in' => LL('cms::validation.not_in', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			//VALIDATION OK

			$user->username = $input['user_username'];
			$user->email = $input['user_email'];
			$user->role_id = $input['user_role'];
			$user->lang = $input['user_lang'];
			$user->editor = $input['user_editor'];
			$user->is_valid = Input::has('is_valid') ? 1 : 0;

			//SET DEFAULT PASSWORD AS USERNAME
			if(empty($input['user_id']))
				$user->password = Hash::make($input['user_username']);

			//UPDATE ROLE LEVEL IN USERS
			$user->role_level = CmsRole::get_role_level($input['user_role']);

			$user->save();

			$uid = $user->id;

			$response = 'success';
			$msg = LL('cms::ajax_resp.user_account_success', CMSLANG)->get();

			$backurl = $input['back_url'];

		} else {

			$rid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.user_account_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'user_id',
			'id' => $uid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}

	//POST SAVE PASSWORD
	public function post_save_password()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			if( ! empty($input['user_id'])) {
				$user = CmsUser::find($input['user_id']);

				//VALIDATION CHECK

				$rules = array(
					'user_password'  => 'required|min:8|confirmed'
				);

				$messages = array(
					'required' => LL('cms::validation.required', CMSLANG)->get(),
					'min' => LL('cms::validation.min.string', CMSLANG)->get(),
					'confirmed' => LL('cms::validation.confirmed', CMSLANG)->get(),
				);

				$validation = Validator::make($input, $rules, $messages);

				if ($validation->fails())
				{
					return json_encode($validation->errors);
				}

				//VALIDATION OK

				$user->password = Hash::make($input['user_password']);

				$user->save();

				$uid = $user->id;

				$response = 'success';
				$msg = LL('cms::ajax_resp.user_password_success', CMSLANG)->get();

				$backurl = $input['back_url'];

			} else {

				$uid = null;

				$response = 'error';
				$msg = LL('cms::ajax_resp.user_nouser_error', CMSLANG)->get();
				$backurl = '#';

			}			

		} else {

			$uid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.user_password_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'user_id',
			'id' => $uid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}


	//POST SAVE DEYAILS
	public function post_save_details()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			
			if( ! empty($input['user_id'])) {
				
				$detail = new CmsUserDetail();
				if( ! empty($input['detail_id']))
					$detail = CmsUserDetail::find($input['detail_id']);

				$detail->user_id = $input['user_id'];
				$detail->name = $input['user_name'];
				$detail->surname = $input['user_surname'];
				$detail->address = $input['user_address'];
				$detail->info = $input['user_info'];
				$detail->number = $input['user_number'];
				$detail->city = $input['user_city'];
				$detail->zip = $input['user_zip'];
				$detail->state = $input['user_state'];
				$detail->country = $input['user_country'];
				$detail->tel = $input['user_tel'];
				$detail->cel = $input['user_cel'];

				$detail->save();

				$did = $detail->id;

				$response = 'success';
				$msg = LL('cms::ajax_resp.user_details_success', CMSLANG)->get();

				$backurl = $input['back_url'];

			} else {

				$did = null;

				$response = 'error';
				$msg = LL('cms::ajax_resp.user_nouser_error', CMSLANG)->get();
				$backurl = '#';

			}			

		} else {

			$uid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.user_details_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'detail_id',
			'id' => $did,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}

}
