<?php

/**
 * Debug a value
 *
 * @param  mixed  $value
 * @return mixed
 */
function D($value)
{
	echo '<pre>' . print_r($value, true) . '</pre>';
}

/**
 * Retrieve a language line.
 *
 * @param  string  $key
 * @param  string  $language
 * @param  array   $replacements
 * @return string
 */
function LL($key, $language = null, $replacements = array())
{
	return Laravel\Lang::line($key, $replacements, $language);
}

/**
 * Load label or value if label not present.
 *
 * @param  string  $where
 * @param  string  $what
 * @return string
 */
function LABEL($where, $what) {

  return (strlen(LL($where.$what, CMSLANG)->get()) > 0 and strpos(LL($where.$what, CMSLANG)->get(), '::') == 0) ?
  LL($where.$what, CMSLANG) : $what;

}

/**
 * Retrieve config array value.
 *
 * @param  array   $config
 * @param  string  $key
 * @return string
 */
function CONF($config, $key)
{
	if($key == '') return array();

	$conf = Config::get($config);

	return $conf[$key];
}

/**
 * Retrieve media type.
 *
 * @param  string  $ext
 * @return string
 */
function MEDIA_TYPE($ext)
{
	switch ($ext) {
		case 'pdf':
			$t = 'pdf';
			break;
		case 'mp3':
			$t = 'mp3';
			break;
		case 'zip':
			$t = 'zip';
			break;
		default:
			$t = 'img';
	}

	return $t;

}

/**
 * Add suffix to filename
 *
 * @param  string  $filename
 * @param  string  $suffix
 * @return string
 */
function MEDIA_NAME($filename, $suffix)
{
  
  if(empty($suffix)) return $filename;
  
  $tmp_ext = substr($filename, -4);
  $tmp_filename = str_replace($tmp_ext, $suffix, $filename) . $tmp_ext;

  //IF FULL PATH, NOT FILENAME
  if(substr_count($tmp_filename, '/img/') > 0) {
	$tmp_filename = str_replace('/img/', '/img/'.Config::get('cms::settings.thumb_path'), $tmp_filename);
  }

  return $tmp_filename;

}

/**
* Convert int text to formatted file size
*
* @param  int
* @param  string
* @return string
*/
function MEDIA_SIZE($size, $type)
{  
  switch($type) {
	case "KB":
	  $filesize = $size * .0009765625; // bytes to KB  
	  break;
	case "MB":
	  $filesize = ($size * .0009765625) * .0009765625; // bytes to MB  
	  break;  
	case "GB":
	  $filesize = (($size * .0009765625) * .0009765625) * .0009765625; // bytes to GB  
	break;
  }

  if($filesize < 0) {  
	return $filesize = 'unknown file size';}
  else {
	return round($filesize, 2).' '.$type;
  }  
}

/**
* Relative to phisical path
*
* @param  string
* @return string
*/
function MEDIA_PATH($path)
{
  return path('public') . substr($path, -(strlen($path)-1));
}

/**
* Remove extension
*
* @param  string
* @return string
*/
function MEDIA_NOEXT($path)
{
  return substr($path, 0, -4);
}

/**
* Remove point of extension
*
* @param  string
* @return string
*/
function MEDIA_NOPOINT($path)
{
  return str_replace('.', '', $path);
}

/**
* Return media dim after resize
*
* @param  int
* @param  int
* @param  int
* @param  int
* @return array
*/
function MEDIA_DIM($ow, $oh, $w, $h)
{
  if($ow >= $oh) {
	$h = intval(($oh * $w) / $ow);
  } else {
	$w = intval(($ow * $h) / $oh);
  }

  return array('w' => $w, 'h' => $h);

}

/**
* Return media url based
*
* @param  int
* @param  int
* @param  int
* @param  int
* @return array
*/
function MEDIA($path)
{
  
  return Config::get('application.url') . '/' . $path;

}

/**
* Parse slug
*
* @param  string
* @return string
*/
function SLUG($path)
{
  return CmsUtility::parse_slug($path);
}

/**
* File from full path
*
* @param  string
* @param 	numeric
* @return string
*/
function PATH2FILE($url, $n = 0)
{
  $slugs = explode('/', $url);

  $c = count($slugs);

  $slug = end($slugs);

  if($n > 0) {
  	$reverse = array_reverse($slugs);

  	for ($i=1; $i < $n+1 ; $i++) { 
  		$slug = $reverse[$i] . '/' . $slug;
  	}
  } 

  return $slug;
}

/**
* Add text preview
*
* @param  string
* @return string
*/
function TEXTPREVIEW($obj, $max = false, $end = '...', $strip_tags = true, $decode = true)
{
  if(!empty($obj)) {

  	$text = (strlen($obj->preview) > 0) ? $obj->preview : $obj->text;

  	if($decode) $text = Marker::decode($text);

  	if($strip_tags) $text = strip_tags($text);

  	if(is_numeric($max)) $text = substr($text, 0, $max) . $end;

  	return $text;

  } else {

  	return '';

  }
}

/**
* Extract image from text
*
* @param  string
* @return string
*/
function TEXT2IMG($text, $w = 320, $h = 200, $key = 0)
{
	$tmp_text = trim($text);
	$tmp_text = Marker::decode($tmp_text);

	preg_match_all('/src="([^"]*)"/i', $tmp_text, $matches);

	if(!empty($matches[1])) {

		$file = PATH2FILE($matches[1][$key]);

		$thumbs = Config::get('cms::theme.thumb');

		foreach ($thumbs as $val) {
		 	$file = str_replace($val['suffix'], '', $file);
		}				

		$url = URL::to_action('cms::image@thumb', array($w, $h, $file));

		return HTML::image($url, '', array('width' => $w, 'height' => $h));

	} else {

		$thumbs = Config::get('cms::theme.thumb');

		$url = URL::to_action('cms::image@thumb', array($w, $h, 'img_default.jpg'));

		return HTML::image($url, '', array('width' => $w, 'height' => $h));

	}
}

/**
 * Inserts values from $arr2 after (or before) $key in $arr1
 * if $key is not found, $arr2 is appended to $arr1 using array_merge()
 *
 * @param $arr1
 *   array to insert into
 * @param $key
 *   key of $arr1 to insert after
 * @param $arr2
 *   array whose values should be inserted
 * @param $before
 *   insert before the given key. defaults to inserting after
 * @return
 *   merged array
 */
function array_insert($arr1, $key, $arr2, $before = FALSE)
{
  $done = FALSE;
  
  foreach($arr1 as $arr1_key => $arr1_val) {
	
	if(!$before) {

	  $new_array[$arr1_key] = $arr1_val;

	}
	
	if($arr1_key == $key && !$done) {
	  
	  foreach($arr2 as $arr2_key => $arr2_val) {

		$new_array[$arr2_key] = $arr2_val;

	  }

	  $done = TRUE;

	}
	
	if($before) {

	  $new_array[$arr1_key] = $arr1_val;

	}

  }
  
  if(!$done) {

	$new_array = array_merge($arr1, $arr2);

  }
  
  return $new_array;
  
}

//EXTRA CONTENT

function getExtra($key = 0)
{
  return CONF('cms::settings.extra_id', $key);
}

function setExtra($value)
{
  return array_search($value, Config::get('cms::settings.extra_id'));
}

function noSlash($string)
{
	return str_replace('/', '', $string);
}

//MARKER HELPERS

function MARKER($marker)
{

	return Marker::decode($marker);

}

//DATE CONVERSION HELPERS

/**
* Date Time format to MySQL format
*
* @param  string
* @return string
*/
function dateTime2Db($datetime)
{

  //get date
  $date = substr($datetime, 0, 10);
  //get time
  $time = substr($datetime, -5);

  //re-format date
  $rsl = explode ('/',$date);
  $rsl = array_reverse($rsl);
  $mysql_date = implode($rsl,'-');

  return $mysql_date . ' ' . $time . ':00';

}

/**
* Date format to MySQL format
*
* @param  string
* @return string
*/
function date2Db($date)
{

  //get date
  $date = substr($date, 0, 10);
  //re-format date
  $rsl = explode ('/',$date);
  $rsl = array_reverse($rsl);
  $mysql_date = implode($rsl,'-');

  return $mysql_date . ' 00:00:00';

}

/**
* MySQL to Date format
*
* @param  string
* @return string
*/
function db2Date($date)
{

  return strftime('%d/%m/%Y', strtotime($date));

}

/**
* Date Time format in future
*
* @param  string
* @return string
*/
function dateTimeFuture($datetime, $when) //'P50Y'
{

  $d = $datetime;

  $date = new DateTime($d);
  $date->add(new DateInterval($when));
  return $date->format('Y-m-d H:i:s');

}
