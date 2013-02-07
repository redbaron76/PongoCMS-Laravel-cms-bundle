<?php

class Cms_Ajax_Download_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	public function post_save_download()
	{
		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$download = new CmsDownload();
			if( ! empty($input['download_id'])) {
				$download = CmsDownload::find($input['download_id']);
			};

			//VALIDATION CHECK

			$rules = array(
				'download_name'  => 'required|alpha_dash|between:2,20|unique:downloads,name,'.$input['download_id'],
			);

			$messages = array(
				'required' => LL('cms::validation.required', CMSLANG)->get(),
				'between' => LL('cms::validation.between.string', CMSLANG)->get(),
				'unique' => LL('cms::validation.unique', CMSLANG)->get(),
				'alpha_dash' => LL('cms::validation.alpha_dash', CMSLANG)->get(),
			);

			$validation = Validator::make($input, $rules, $messages);

			if ($validation->fails())
			{
				return json_encode($validation->errors);
			}

			$download->name = strtolower($input['download_name']);

			$download->save();

			//DELETE CACHE
			if(CACHE) Cache::forget('file_list_'.strtolower($input['download_name']));

			$did = $download->id;

			// Empty template
			$template = '';			

			if(Input::get('file_id') !== '') {

				$files = Input::get('file_id');

				if(is_array($files)) {

					foreach ($files as $fid) {

						$check = DB::table('files_downloads')->where_cmsfile_id($fid)->where_cmsdownload_id($did)->first();

						if(empty($check)) {

							$download->files()->attach($fid, array('order_id' => Config::get('cms::settings.order')));

						}

						$img = CmsFile::find($fid);

						// Template returned
						$template .= '<li id="'.$did.'_'.$fid.'" class="span1">';
						$template .= '<a class="thumbnail" rel="tooltip" data-original-title="'.$img->name.'" href="'.BASE.$img->path.'">';
						$template .= '<img src="'.BASE.$img->thumb.'" />';
						$template .= '</a>';
						$template .= '</li>';

					}

					//DELETE NOT IN
					DB::table('files_downloads')->where_cmsdownload_id($did)->where_not_in('cmsfile_id', $files)->delete();
				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.download_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

				// Inject container
				$inject = 'ul.sortable';
				$detach = true;

			} else {

				//DELETE ALL DOWNLOAD_ID
				DB::table('files_downloads')->where_cmsdownload_id($did)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.download_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

				$template = '';
				$inject = '';
				$detach = true;

			}

		} else {

			$response = 'error';
			$msg = LL('cms::ajax_resp.download_save_error', CMSLANG)->get();
			$backurl = '#';

			$template = '';
			$inject = '';
			$detach = true;

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'download_id',
			'id' => $did,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl,
			'detach' => $detach,
			'inject' => $inject,
			'template' => $template
		);

		return json_encode($data);
	}


	//POST ADD download
	public function post_add_download()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			if(Input::get('download_id') !== '') {

				$fid = Input::get('file_id');
				$downloads = Input::get('download_id');

				if(is_array($downloads)) {

					foreach ($downloads as $key => $gid) {

						$check = DB::table('files_downloads')->where_cmsfile_id($fid)->where_cmsdownload_id($gid)->count();

						if($check == 0) {

							$download = CmsDownload::find($gid);

							$add_array = array(
								'order_id' => Config::get('cms::settings.order')
							);
							
							$download->files()->attach($fid, $add_array);

						}

					}

					//DELETE NOT IN
					DB::table('files_downloads')->where_cmsfile_id($fid)->where_not_in('cmsdownload_id', $downloads)->delete();

					$response = 'success';
					$msg = LL('cms::ajax_resp.download_save_success', CMSLANG)->get();
					$backurl = Input::get('back_url');

				} else {

					//DELETE ALL
					DB::table('files_downloads')->where_cmsfile_id($fid)->delete();

					$response = 'success';
					$msg = LL('cms::ajax_resp.download_save_success', CMSLANG)->get();
					$backurl = '#';

				}

			} else {

				$response = 'error';
				$msg = LL('cms::ajax_resp.download_save_error', CMSLANG)->get();
				$backurl = '#';

			}

		} else {

			$response = 'error';
			$msg = LL('cms::ajax_resp.download_save_error', CMSLANG)->get();
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


	//ORDER MENU
	public function post_order_download()
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

					DB::table('files_downloads')->where_cmsdownload_id($p[0])->where_cmsfile_id($p[1])->update($update);

				}
				
			}
		}

		return true;

	}


}
