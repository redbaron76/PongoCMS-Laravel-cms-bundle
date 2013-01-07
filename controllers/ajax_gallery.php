<?php

class Cms_Ajax_Gallery_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	public function post_save_gallery()
	{
		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$gallery = new CmsGallery();
			if( ! empty($input['gallery_id'])) {
				$gallery = CmsGallery::find($input['gallery_id']);
			};

			//VALIDATION CHECK

			$rules = array(
				'gallery_name'  => 'required|alpha_dash|between:2,30|unique:galleries,name,'.$input['gallery_id'],
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

			$gallery->name = strtolower($input['gallery_name']);
			$gallery->thumb = $input['gallery_thumb'];

			$gallery->save();

			//DELETE CACHE
			if(CACHE) Cache::forget('img_gallery_'.strtolower($input['gallery_name']));

			$gid = $gallery->id;

			// Empty template
			$template = '';

			if(Input::get('file_id') !== '') {

				$files = Input::get('file_id');

				if(is_array($files)) {

					foreach ($files as $fid) {

						$check = DB::table('files_galleries')->where_cmsfile_id($fid)->where_cmsgallery_id($gid)->first();

						if(empty($check)) {

							$gallery->files()->attach($fid, array('order_id' => Config::get('cms::settings.order')));

						}

						$img = CmsFile::find($fid);

						// Template returned
						$template .= '<li id="'.$gid.'_'.$fid.'" class="span1">';
						$template .= '<a class="thumbnail" rel="tooltip" data-original-title="'.$img->name.'" href="'.BASE.$img->path.'">';
						$template .= '<img src="'.BASE.$img->thumb.'" />';
						$template .= '</a>';
						$template .= '</li>';

					}

					//DELETE NOT IN
					DB::table('files_galleries')->where_cmsgallery_id($gid)->where_not_in('cmsfile_id', $files)->delete();
				}

				$response = 'success';
				$msg = LL('cms::ajax_resp.gallery_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

				// Inject container
				$inject = 'ul.sortable';
				$detach = true;

			} else {

				//DELETE ALL GALLERY_ID
				DB::table('files_galleries')->where_cmsgallery_id($gid)->delete();

				$response = 'success';
				$msg = LL('cms::ajax_resp.gallery_save_success', CMSLANG)->get();
				$backurl = $input['back_url'];

				$template = '';
				$inject = '';
				$detach = true;

			}

		} else {

			$response = 'error';
			$msg = LL('cms::ajax_resp.gallery_save_error', CMSLANG)->get();
			$backurl = '#';

			$template = '';
			$inject = '';
			$detach = true;

		}

		$data = array(
			'auth' => $auth,
			'cls' => 'gallery_id',
			'id' => $gid,
			'response' => $response,
			'message' => $msg,
			'backurl' => $backurl,
			'detach' => $detach,
			'inject' => $inject,
			'template' => $template
		);

		return json_encode($data);
	}

	//ORDER MENU
	public function post_order_gallery()
	{

		$order = Input::get('order');
		
		if(is_array($order)) {
			
			foreach($order as $order_id => $item) {
				$order_id++;
				$p = explode("_", $item);

				$update = array(
					'order_id' => $order_id
				);

				DB::table('files_galleries')->where_cmsgallery_id($p[0])->where_cmsfile_id($p[1])->update($update);

			}
			
		}

		return true;

	}


}
