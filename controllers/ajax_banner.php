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

		if($auth and is_numeric(AUTHORID)) {

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

			// Empty template
			$template = '';

			if(Input::get('file_id') !== '') {

				$files = Input::get('file_id');
				$url = Input::get('url');
				$date_off = Input::get('date_off');
				$is_blank = Input::get('is_blank', array());
				$wm = Input::get('wm', array());

				if(is_array($files)) {

					foreach ($files as $key => $fid) {

						$check = DB::table('files_banners')->where_cmsfile_id($fid)->where_cmsbanner_id($bid)->first();

						$blank = (array_key_exists($key, $is_blank)) ? 1 : 0;

						$wwm = (array_key_exists($key, $wm)) ? 1 : 0;

						if(empty($date_off[$key])) {

							$off_date = dateTimeFuture(date("Y-m-d H:i:s"), 'P5Y');

						} else {

							$off_date = date2Db($date_off[$key]);

						}

						if(empty($check)) {

							$add_array = array(
								'url' => $url[$key],
								'date_off' => $off_date,
								'is_blank' => $blank,
								'wm' => $wwm,
								'order_id' => Config::get('cms::settings.order')
							);
							
							$banner->files()->attach($fid, $add_array);

						} else {

							$update_array = array(
								'url' => $url[$key],
								'date_off' => $off_date,
								'is_blank' => $blank,
								'wm' => $wwm,
							);

							DB::table('files_banners')
							->where_cmsfile_id($fid)
							->where_cmsbanner_id($bid)
							->update($update_array);

						}

						$img = CmsFile::find($fid);

						// Template returned
						$template .= '<li id="'.$bid.'_'.$fid.'" class="span1">';
						$template .= '<a class="thumbnail" rel="tooltip" data-original-title="'.$img->name.'" href="'.BASE.$img->path.'">';
						$template .= '<img src="'.BASE.$img->thumb.'" />';
						$template .= '</a>';
						$template .= '</li>';

					}

				} else {

					$template = '';

				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.banner_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

				// Inject container
				$inject = 'ul.sortable';
				$detach = true;

			} else {

				//DELETE ALL GALLERY_ID
				DB::table('files_banners')->where_cmsbanner_id($bid)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.banner_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

				$template = '';
				$inject = '';
				$detach = true;

			}

		} else {

			$response = 'error';
			$msg = LL('cms::ajax_resp.banner_save_error', CMSLANG)->get();
			$backurl = '#';

			$template = '';
			$inject = '';
			$detach = true;

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'banner_id',
			'id' => $bid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl,
			'detach' => $detach,
			'inject' => $inject,
			'template' => $template
		);

		return json_encode($data);
	}


	//POST ADD BANNER
	public function post_add_banner()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			if(Input::get('banner_id') !== '') {

				$fid = Input::get('file_id');
				$banners = Input::get('banner_id');

				if(is_array($banners)) {

					foreach ($banners as $key => $bid) {

						$check = DB::table('files_banners')->where_cmsfile_id($fid)->where_cmsbanner_id($bid)->count();

						if($check == 0) {

							$banner = CmsBanner::find($bid);

							$add_array = array(
								'url' => '',
								'date_off' => dateTimeFuture(date("Y-m-d H:i:s"), 'P5Y'),
								'is_blank' => 0,
								'wm' => 0,
								'order_id' => Config::get('cms::settings.order')
							);
							
							$banner->files()->attach($fid, $add_array);

						}

					}

					//DELETE NOT IN
					DB::table('files_banners')->where_cmsfile_id($fid)->where_not_in('cmsbanner_id', $banners)->delete();

					$response = 'success';
					$msg = LL('cms::ajax_resp.banner_save_success', CMSLANG)->get();
					$backurl = Input::get('back_url');

				} else {

					//DELETE ALL
					DB::table('files_banners')->where_cmsfile_id($fid)->delete();

					$response = 'success';
					$msg = LL('cms::ajax_resp.banner_save_success', CMSLANG)->get();
					$backurl = Input::get('back_url');

				}

			} else {

				$response = 'error';
				$msg = LL('cms::ajax_resp.banner_save_error', CMSLANG)->get();
				$backurl = '#';

			}

		} else {

			$response = 'error';
			$msg = LL('cms::ajax_resp.banner_save_error', CMSLANG)->get();
			$backurl = '#';

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'file_id',
			'id' => $fid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl
		);

		return json_encode($data);

	}

	//ORDER BANNER
	public function post_order_banner()
	{

		$order = Input::get('order');
		
		if(is_array($order)) {
			
			foreach($order as $order_id => $item) {
				$order_id++;
				$p = explode("_", $item);

				$update = array(
					'order_id' => $order_id
				);

				DB::table('files_banners')
					->where_cmsbanner_id($p[0])
					->where_cmsfile_id($p[1])
					->update($update);
			}
			
		}

		return true;

	}


}
