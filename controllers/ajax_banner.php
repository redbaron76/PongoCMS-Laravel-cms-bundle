<?php

class Cms_Ajax_Banner_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	public function post_save_banner()
	{
		$auth = Auth::check();

		if($auth) {

			$input = Input::get();

			//GRAB DATA
			$banner = new CmsBanner();
			if( ! empty($input['banner_id'])) {
				$banner = CmsBanner::find($input['banner_id']);
			};

			//VALIDATION CHECK

			$rules = array(
				'banner_name'  => 'required|alpha_dash|between:2,20|unique_lang:'.$input['banner_id'].','.$input['banner_lang'].',banners,name',
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'alpha_dash' => LL('cms::validation.alpha_dash', CMSLANG)->get(),
				'unique_lang' => LL('cms::validation.unique', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			$banner->name = strtolower($input['banner_name']);
			$banner->lang = $input['banner_lang'];

			$banner->save();

			//DELETE CACHE
			if(CACHE) Cache::forget('img_banner_'.strtolower($input['banner_name']));

			$bid = $banner->id;			

			if(Input::has('file_id')) {

				$files = Input::get('file_id');
				$url = Input::get('url');
				$date_off = Input::get('date_off');
				$is_blank = Input::get('is_blank');

				if(is_array($files)) {

					foreach ($files as $key => $fid) {

						$check = $banner->files()->pivot()->where_cmsfile_id($fid)->where_cmsbanner_id($bid)->first();

						$blank = (array_key_exists($key, $is_blank)) ? 1 : 0;

						if(empty($check)) {

							$add_array = array(
								'url' => $url[$key],
								'date_off' => date2Db($date_off[$key]),
								'is_blank' => $blank,
								'order_id' => 1000000
							);
							
							$banner->files()->attach($fid, $add_array);

						} else {

							$update_array = array(
								'url' => $url[$key],
								'date_off' => date2Db($date_off[$key]),
								'is_blank' => $blank,
							);

							DB::table('files_banners')
							->where_cmsfile_id($fid)
							->where_cmsbanner_id($bid)
							->update($update_array);

						}

					}

					//DELETE NOT IN
					$banner->files()->pivot()->where_cmsbanner_id($bid)->where_not_in('cmsfile_id', $files)->delete();
				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.banner_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			} else {

				//DELETE ALL GALLERY_ID
				$banner->files()->pivot()->where_cmsbanner_id($bid)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.banner_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			}

		} else {

			$response = 'error';
			$msg = LL('cms::ajax_resp.banner_save_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'banner_id',
			'id' => $bid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);
	}

	//ORDER BANNER
	public function post_order_banner()
	{

		if(Input::has('order')) {

			$order = Input::get('order');
			
			if(is_array($order)) {
				
				foreach($order as $order_id => $item) {
					$order_id++;
					$p = explode("_", $item);

					$update = array(
						'order_id' => $order_id
					);

					DB::table('files_banners')->where_cmsbanner_id($p[0])->where_cmsfile_id($p[1])->update($update);
				}
				
			}
		}

		return true;

	}


}