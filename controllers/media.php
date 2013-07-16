<?php

class Cms_Media_Controller extends Cms_Base_Controller {

	//Get public FILENAME
	public function get_get($file_name = '')
	{
		
		if(!empty($file_name)) {

			$_public = path('public');
			$_data = Config::get('cms::settings.data');
			$_ext = File::extension($file_name);

			$img_mimes = array('jpg', 'jpeg', 'gif', 'png');

			if (in_array($_ext, $img_mimes)) $_ext = 'img';

			$_path = $_public.$_data.'/'.$_ext . '/';

			return Response::download($_path.$file_name);

		}

		return Response::error('404');
		
	}

}