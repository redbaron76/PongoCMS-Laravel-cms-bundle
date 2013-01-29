<?php

class Cms_Image_Controller extends Cms_Base_Controller {

	//DEFAULT IMAGE IF NOT FOUND
	public static $default = 'bundles/cms/img/img_default.jpg';

	//DEFAULT IMG PATH
	public static $img_path = 'img/';

	//CROP FROM CENTER IMAGE
	public function get_crop($x = 0, $y = 0, $w = 100, $h = 100, $wm = 'no', $filename = '')
	{

		$path = path('public') . Config::get('cms::settings.data') . self::$img_path . $filename;

		if(!file_exists($path)) $path = path('public') . self::$default;

		$thumb = PhpThumbFactory::create($path);

		$thumb->crop($x, $y, $w, $h);
		
		if($wm == 'wm') $thumb->addLogo(path('public') . Config::get('cms::theme.watermark.path'),
								Config::get('cms::theme.watermark.horizontal'),
								Config::get('cms::theme.watermark.vertical'),
								$thumb);
			
		$thumb->show();

	}
	

	//CROP FROM CENTER IMAGE
	public function get_cropcenter($w = 100, $h = 100, $wm = 'no', $filename = '')
	{

		$path = path('public') . Config::get('cms::settings.data') . self::$img_path . $filename;

		if(!file_exists($path)) $path = path('public') . self::$default;

		$thumb = PhpThumbFactory::create($path);

		$thumb->cropFromCenter($w, $h);
		
		if($wm == 'wm') $thumb->addLogo(path('public') . Config::get('cms::theme.watermark.path'),
								Config::get('cms::theme.watermark.horizontal'),
								Config::get('cms::theme.watermark.vertical'),
								$thumb);

		$thumb->show();

	}


	//ADAPTIVE THUMB IMAGE
	public function get_thumb($w = 100, $h = 100, $wm = 'no', $filename = '')
	{

		$path = path('public') . Config::get('cms::settings.data') . self::$img_path . $filename;

		if(!file_exists($path)) $path = path('public') . self::$default;

		$thumb = PhpThumbFactory::create($path);

		$thumb->adaptiveResize($w, $h);

		if($wm == 'wm') $thumb->addLogo(path('public') . Config::get('cms::theme.watermark.path'),
								Config::get('cms::theme.watermark.horizontal'),
								Config::get('cms::theme.watermark.vertical'),
								$thumb);

		$thumb->show();

	}


	//RESIZE IMAGE
	public function get_resize($w = 100, $h = 100, $wm = 'no', $filename = '')
	{

		$path = path('public') . Config::get('cms::settings.data') . self::$img_path . $filename;

		if(!file_exists($path)) $path = path('public') . self::$default;

		$thumb = PhpThumbFactory::create($path);	

		$thumb->resize($w, $h);

		if($wm == 'wm')	$thumb->addLogo(path('public') . Config::get('cms::theme.watermark.path'),
								Config::get('cms::theme.watermark.horizontal'),
								Config::get('cms::theme.watermark.vertical'),
								$thumb);

		$thumb->show();

	}


	//PERCENT RESIZE IMAGE
	public function get_percent($p = 100, $wm = 'no', $filename = '')
	{

		$path = path('public') . Config::get('cms::settings.data') . self::$img_path . $filename;

		if(!file_exists($path)) $path = path('public') . self::$default;

		$thumb = PhpThumbFactory::create($path);

		$thumb->resizePercent($p);

		if($wm == 'wm') $thumb->addLogo(path('public') . Config::get('cms::theme.watermark.path'),
								Config::get('cms::theme.watermark.horizontal'),
								Config::get('cms::theme.watermark.vertical'),
								$thumb);

		$thumb->show();

	}

}
