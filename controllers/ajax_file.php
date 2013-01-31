<?php

class Cms_Ajax_File_Controller extends Cms_Base_Controller {
	
	//FILTERS
	public function __construct()
	{
		parent::__construct();
		//Must be logged
		//$this->filter('before', 'cms_no_auth');
	}

	public function post_save_image_text()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$text = CmsFileText::where_file_id($input['file_id'])
			->where_lang($input['file_lang'])
			->first();

			if(empty($text)) {
				$text = new CmsFileText();	
			}

			$text->file_id = $input['file_id'];

			$text->alt = $input['filetext_alt'];
			$text->title = $input['filetext_title'];
			$text->caption = $input['filetext_caption'];
			$text->lang = $input['file_lang'];

			$text->save();

			$fid = $input['file_id'];

			$response = 'success';
			$msg = LL('cms::ajax_resp.filetext_text_success', CMSLANG)->get();
			$backurl = $input['back_url'];

		} else {

			$fid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.filetext_text_error', CMSLANG)->get();
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

	public function post_save_file_text()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA
			$text = CmsFileText::where_file_id($input['file_id'])
			->where_lang($input['file_lang'])
			->first();

			if(empty($text)) {
				$text = new CmsFileText();	
			}

			$text->file_id = $input['file_id'];

			$text->label = Input::has('filetext_label') ? $input['filetext_label'] : '';
			$text->alt = Input::has('filetext_alt') ? $input['filetext_alt'] : '';
			$text->title = Input::has('filetext_title') ? $input['filetext_title'] : '';
			$text->caption = Input::has('filetext_caption') ? $input['filetext_caption'] : '';
			$text->lang = $input['file_lang'];

			$text->save();

			$fid = $input['file_id'];

			$response = 'success';
			$msg = LL('cms::ajax_resp.filetext_text_success', CMSLANG)->get();
			$backurl = $input['back_url'];

		} else {

			$fid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.filetext_text_error', CMSLANG)->get();
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

	public function post_save_available()
	{
		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$fid = Input::get('file_id');

			$pages = Input::get('page_id');

			if(!empty($fid)) {

				$now = date('Y-m-d H:i:s');

				$file = CmsFile::find($fid);

				if(is_array($pages)) {

					foreach ($pages as $pid) {

						$check = DB::table('files_pages')->where_cmsfile_id($fid)->where_cmspage_id($pid)->first();

						if(empty($check)) {
							$file->pages()->attach($pid);
						}

					}

					//DELETE NOT IN
					DB::table('files_pages')->where_cmsfile_id($fid)->where_not_in('cmspage_id', $pages)->delete();
				}

				// RESET CACHE
				if(CACHE) Cache::forget('file_'.$fid.'_details');

				$response = 'success';
				$msg = LL('cms::ajax_resp.filename_fileavailable_success', CMSLANG)->get();
				$backurl = '#';


			} else {

				$fid = null;

				$response = 'error';
				$msg = LL('cms::ajax_resp.filename_fileavailable_error', CMSLANG)->get();
				$backurl = '#';

			}

		}  else {

			$fid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.filename_fileavailable_error', CMSLANG)->get();
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

	public function post_save_filename()
	{

		$auth = Auth::check();

		if($auth and is_numeric(AUTHORID)) {

			$input = Input::get();

			//GRAB DATA			
			if( ! empty($input['file_id'])) {

				//GRAB DATA
				$file = CmsFile::find($input['file_id']);

				$fid = $file->id;
				$path = MEDIA_PATH($file->path);
				$name = $file->name;
				$ext = '.'.$file->ext;
				$filename = str_replace($ext, '', $name);
				$newname = $input['file_name'];

				//VALIDATION CHECK

				$rules = array(
					'file_name'  => 'required|alpha_dash|unique_filename:'.$file->ext.',name'
				);

				$messages = array(
					'required' => LL('cms::validation.required', CMSLANG)->get(),
					'unique_filename' => LL('cms::validation.unique_filename', CMSLANG)->get(),
					'alpha_dash' => LL('cms::validation.alpha_dash', CMSLANG)->get(),
				);

				$validation = Validator::make($input, $rules, $messages);

				if ($validation->fails())
				{
					return json_encode($validation->errors);
				}

				//VALIDATION OK				

				//RENAME DB

				//RENAME NAME
				$file->name = str_replace($filename, $newname, $name);
				//RENAME PATH
				$file->path = str_replace($filename, $newname, $file->path);
				//RENAME THUMB
				$file->thumb = str_replace($filename, $newname, $file->thumb);

				$file->save();

				//RENAME DISK

				//RENAME FILE
				if(file_exists($path)) rename($path, str_replace($filename, $newname, $path));

				//LOOP ALL THUMBS AND RENAME
				foreach (Config::get('cms::theme.thumb') as $option) {
					
					$thumb = MEDIA_NAME($path, $option['suffix']);
					if(file_exists($thumb)) rename($thumb, str_replace($filename, $newname, $thumb));

				}


				$response = 'success';
				$msg = LL('cms::ajax_resp.filename_filename_success', CMSLANG)->get();
				$backurl = $input['back_url'];

			} else {

				$fid = null;

				$response = 'error';
				$msg = LL('cms::ajax_resp.filename_filename_error', CMSLANG)->get();
				$backurl = '#';

			}			

		} else {

			$fid = null;

			$response = 'error';
			$msg = LL('cms::ajax_resp.filename_filename_error', CMSLANG)->get();
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


	public function post_file_text_lang()
	{
		if(Input::has('file_lang') and Input::has('file_id')) {

			$lang = Input::get('file_lang');
			$fid = Input::get('file_id');

			$text = CmsFileText::where_file_id($fid)
			->where_lang($lang)
			->first();

			$data = array(
				'alt' => (!empty($text)) ? $text->alt : '',
				'title' => (!empty($text)) ? $text->title : '',
				'label' => (!empty($text)) ? $text->label : '',
			);

			return json_encode($data);

		}

		return false;

	}

}
