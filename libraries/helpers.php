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
 * @param  string   $config
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
 * Check config setting
 *
 * @param  string   $config
 * @param  string  $key
 * @return bool
 */
function IS($config, $key)
{
	return Config::get($config) === $key ? true : false;
}

/**
 * Check object is_null and set alternatives
 *
 * @param  obj
 * @param  obj property
 * @param  string  $alt
 * @return bool
 */
function NOTNULL($obj, $prop, $alt)
{
	return (!is_null($obj) and !empty($obj->$prop)) ? $obj->$prop : '-';
}

/**
 * Set is_valid true or false depending on /preview reequest
 *
 * @return bool
 */
function VALID($base = SLUG_FULL)
{
	if($base == SLUG_FIRST) return 1;

	return SLUG_PREVIEW ? 0 : 1;
}

/**
 * Set class "disabled" to nav tabs when new
 *
 * @param  int  $item
 * @return string
 */
function DISABLED($item)
{
	return is_numeric($item) ? '' : HTML::attributes(array('class' => 'disabled'));
}

/**
 * Retrieve media type.
 *
 * @param  string  $ext
 * @return string
 */
function MEDIA_TYPE($ext)
{
	
	$img_mimes = array('jpg', 'jpeg', 'gif', 'png');

	if (in_array($ext, $img_mimes)) {

		$t = 'img';

	} else {

		$t = $ext;

	}

	return $t;

}

/**
 * Add suffix to filename
 *
 * @param  string  $filename
 * @param  string  $suffix
 * @param  bool    $with_path
 * @return string
 */
function MEDIA_NAME($filename, $suffix, $with_path = false)
{
	
	if(empty($suffix)) return $filename;
	
	$tmp_ext = substr($filename, -4);
	$tmp_filename = str_replace($tmp_ext, $suffix, $filename) . $tmp_ext;

	//IF FULL PATH, NOT FILENAME
	if(substr_count($tmp_filename, '/img/') > 0) {
	$tmp_filename = str_replace('/img/', '/img/'.Config::get('cms::settings.thumb_path'), $tmp_filename);
	}

	if($with_path) return URL::to(Config::get('cms::settings.data').'img/'.Config::get('cms::settings.thumb_path').$tmp_filename);

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
* Get media file url
*
* @param string
* @return string
*/
function MEDIA_URL($file_name)
{	
	return URL::to('cms/media/get/'.$file_name);
}

/**
* Return media url based
*
* @param  string
* @return string
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
function TEXTPREVIEW($obj, $max = false, $strip_tags = false, $decode = true, $end = '...')
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

		$url = URL::to_action('cms::image@thumb', array($w, $h, 'no', $file));

		return HTML::image($url, '', array('width' => $w, 'height' => $h));

	} else {

		$thumbs = Config::get('cms::theme.thumb');

		$url = URL::to_action('cms::image@thumb', array($w, $h, 'no', 'img_default.jpg'));

		return HTML::image($url, '', array('width' => $w, 'height' => $h));

	}
}

/**
 * Format Prettify text
 *
 * @param  string
 * @return string
 */
function PRETEXT($text)
{
	if((strpos($text, '<pre') !== false) and (strpos($text, 'prettyprint') !== false)) {

		$content_processed = preg_replace_callback(
			'#\<pre class=["\']prettyprint[\'"]\>(.+?)\<\/pre\>#s',
			create_function(
				'$matches',
				'return "<pre class=\'prettyprint\'>".ENCODETEXT($matches[1])."</pre>";'
			), $text
		);

		return $content_processed;

	}

	return $text;
}

/**
 * Encode Prettify text
 *
 * @param  string
 * @return string
 */
function ENCODETEXT($text)
{
	if(!empty($text)) {

		$text = htmlentities($text);
		$text = str_replace(' ', '&nbsp;', $text);

		return $text;

	}

	return $text;
}

/**
 * Decode Prettify text
 *
 * @param  string
 * @return string
 */
function DECODETEXT($text)
{
	if(!empty($text)) {

		$text = html_entity_decode($text);
		$text = str_replace('&nbsp;', ' ', $text);

		return $text;

	}	

	return $text;
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

//VIEW HELPER - Fallback to default if not present

function LOAD_VIEW($tpl)
{

	$tpl_view = 'cms::theme.'.THEME.'.partials.markers.'.$tpl;
	$default_view = 'cms::theme.default.partials.markers.'.$tpl;

	return View::exists($tpl_view) ? View::make($tpl_view) : View::make($default_view); 

}

//DATE CONVERSION HELPERS

/**
 * Get datetime format
 *
 * @param  bool  $time
 * @return string
 */
function GET_DATETIME($time = true)
{
	$date_format = Config::get('cms::settings.dateformat');
	if($time) $date_format = $date_format . ' ' . Config::get('cms::settings.timeformat');

	return $date_format;
}

/**
* Date Time format to MySQL format
*
* @param  string
* @return string
*/
function dateTime2Db($datetime)
{

	if(!empty($datetime)) {

		$d = DateTime::createFromFormat(GET_DATETIME(), $datetime);
		
		$mysql_datetime = $d->format('Y-m-d H:i');

		return $mysql_datetime . ':00';
	}

	return '0000-00-00 00:00:00';

}

/**
* Date format to MySQL format
*
* @param  string
* @return string
*/
function date2Db($date)
{

	if(!empty($date)) {

		$d = DateTime::createFromFormat(GET_DATETIME(false), $date);
		
		$mysql_date = $d->format('Y-m-d');

		return $mysql_date . ' 00:00:00';
	}

	return '0000-00-00';

}

/**
* MySQL to Date format
*
* @param  string
* @return string
*/
function db2Date($date, $with_time = true)
{

	if(!empty($date)) {

		$date = DateTime::createFromFormat('Y-m-d H:i', substr($date, 0, -3));

		return $date->format(GET_DATETIME($with_time));
	}

	return GET_DATETIME($with_time);

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
