<?php

class Marker extends CustomMarker {

	/**
    * Substitute tag in text with specific HTML
    *
    * @param  string  $text
    * @return string
    */
	public static function decode($text)
	{

		$tmp_text = trim($text);

		//con json
		preg_match_all('/\[\$([!?A-Z_]+)\[([^$]+)?\]\]/i', $tmp_text, $matches);

		foreach($matches[0] as $key => $value) {

			//METHOD TO EXECUTE
			$method = $matches[1][$key];

			//STRING FOUND
			$found = $matches[2][$key];

			// CLEAN LAST COMMA
			if(substr($found, -1) == ',') $found = substr($found, 0, -1);

			//CLEAN HTML
			$found = strip_tags($found);
			$found = html_entity_decode($found);

			$v = json_decode($found, true);

			if(!is_array($v)) $v = array();

			$vars = $v;

			if(method_exists('Marker', $method)) {

				//EXECUTE METHOD IF FOUND
				$replace = call_user_func('self::' . $method , $vars);

				//SUBSTITUTE TAG IN TEXT
				if($replace or empty($replace)) $tmp_text = str_replace($value, $replace, $tmp_text);

			}

			//SKIP ! EXECUTION
			if(substr($method, 0, 1) == '!') $tmp_text = str_replace('$!', '&#36;', $tmp_text);

		}

		//IMG PATH REPLACE
		$img_path = '/' . Config::get('cms::settings.data');
		$url = Config::get('application.url');
		$tmp_text = str_replace('"'.$img_path, '"'.$url.$img_path, $tmp_text);

		return $tmp_text;
	}

	/**
    * BACK Marker - Shows a back button
    *
	* [$BACK[{
	*	"label":"<label back>"	=> (default: cms::button.back)
	*	"url":""				=> OPTIONAL (default: SLUG_BACK)
	*	"id":"<id>",			=> OPTIONAL (id of <a>)
	*	"class":"<class>",		=> OPTIONAL (default: back)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function BACK($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_label = LL('cms::button.back', SITE_LANG);
		if(isset($label) and !empty($label)) $_label = $label;

		$_url = SLUG_BACK;
		if(isset($url) and !empty($url)) $_url = $url;

		$_id = null;
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'back';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'back';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_url)) {

			$options = array(
				'id' => $_id,
				'class' => $_class,
			);

			$view = LOAD_VIEW($_tpl);
			$view['label'] 	= $_label;
			$view['url'] 	= $_url;
			$view['options'] 	= HTML::attributes($options);

			return $view;

		}

	}

	/**
    * BANNER Marker - Shows a banner rotator as saved in Services / Banner
    *
	* [$BANNER[{
	*	"name":"<banner name>",
	*	"thumb":"<thumb type>"	=> (default: none)
	*	"type":"<slider name>"	=> (default: none | available: nivo)
	*	"theme":"<theme>"		=> (default: default)
	*	"caption":"false"		=> (default: false)
	*	"w":"",
	*	"h":"",
	*	"class":"<class>",		=> OPTIONAL
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function BANNER($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_name = '';
		if(isset($name) and !empty($name)) $_name = $name;

		$_type = '';
		if(isset($type) and !empty($type)) $_type = $type;

		$_thumb = '';
		if(isset($thumb) and !empty($thumb)) $_thumb = $thumb;

		$_theme = 'default';
		if(isset($theme) and !empty($theme)) $_theme = $theme;

		$_caption = false;
		if(isset($caption) and !empty($caption) and $caption == 'true') $_caption = true;

		$_w = null;
		if(isset($w) and !empty($w)) $_w = $w;

		$_h = null;
		if(isset($h) and !empty($h)) $_h = $h;

		$_class = 'banner';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'banner';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//FORCE NIVO TPL
		if(isset($type) and $type == 'nivo') $_tpl = 'nivo';


		if(!empty($_name)) {

			if($_type == 'nivo') {
				//LOAD NIVOSLIDER LIBS
				Asset::container('header')->add('nivoslidercss', 'bundles/cms/css/nivoslider.css', 'site_css');
				Asset::container('header')->add('nivo'.$_theme, 'bundles/cms/nivoslider/themes/'.$_theme.'/'.$_theme.'.css', 'nivoslidercss');
				Asset::container('footer')->add('nivosliderjs', 'bundles/cms/js/jquery.nivoslider.js', 'jquery_lib');
				Asset::container('footer')->add('banner', 'js/markers/banner.js', 'site_js');
			}

			//CACHE DATA
			if(CACHE) {

				$list = Cache::remember('img_banner_'.$_name.'_'.SITE_LANG, function() use ($_name) {
					return CmsBanner::with(array(
						'files' => function($query) {
						
							$query->where('files_banners.date_off', '>=', date('Y-m-d H:i:s'));

						},
						'files.filetexts' => function($query) {
						
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_name)->first();
				}, 1440);

			} else {

				$list = CmsBanner::with(array(
					'files' => function($query) {
						
						$query->where('files_banners.date_off', '>=', date('Y-m-d H:i:s'));

					},
					'files.filetexts' => function($query) {
					
						$query->where('lang', '=', SITE_LANG);

					}))->where_name($_name)->first();
			}

			//Load file lable and title
			if(!empty($list->files)) {

				$images = $list->files;
				$attr = $list->files;

			} else {
				
				$images = array();
				$attr = '';

			}

		} else {

			$images = array();
			$attr = '';

		}

		$options = array(
			'id' => $_name,
			'class' => $_class,
		);

		$thumbs = CONF('cms::theme.thumb', $_thumb);

		$view = LOAD_VIEW($_tpl);
		$view['images'] 	= $images;
		$view['thumb'] 		= (strlen($_thumb) > 0 and array_key_exists('suffix', $thumbs)) ? $thumbs['suffix'] : '';
		$view['theme'] 		= $_theme;
		$view['attr'] 		= $attr;
		$view['caption'] 	= $_caption;
		$view['w']			= $_w;
		$view['h']			= $_h;
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * BLOGVIEW Marker - Shows a list of last n blogs
    *
	* [$BLOGVIEW[{
	*	"source":"<source label>",	=> (available: blogs, products...)
	*	"time":"<time label>",		=> (available: future, past, null - default: null)
	*	"n":"<n items per page>",	=> (default: 5)
	*	"order":"desc",				=> OPTIONAL (asc, desc default: desc)
	*	"id":"<id>",				=> OPTIONAL <ul> id
	*	"class":"<class>",			=> OPTIONAL <ul><li> class (default: list)
	*	"tpl":"<tpl_name>"			=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function BLOGVIEW($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_time = null;
		if(isset($time) and !empty($time)) $_time = $time;

		$_n = 5;
		if(isset($n) and !empty($n)) $_n = $n;

		$_order = 'desc';
		if(isset($order) and !empty($order)) $_order = $order;

		$_id = 'blogview';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'list';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'blogview';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//CHECK SOURCE CLASS

		if($_n > 0) {

			$_sign = '<';
			if($_time == 'future') $_sign = '>';

			//CACHE DATA
			if(CACHE) {

				$list = Cache::remember('blog_last_'.SITE_LANG, function() use ($_sign, $_order, $_n) {

					return CmsBlog::with(array('pages'))
						->where_lang(SITE_LANG)
						->where('datetime_on', $_sign.'=', date('Y-m-d H:i:s'))
						->where('datetime_off', '>', date('Y-m-d H:i:s'))
						->where_is_valid(1)
						->order_by('datetime_on', $_order)
						->take($_n)
						->get();

				}, 5);

			} else {

				$list = CmsBlog::with(array('pages'))
						->where_lang(SITE_LANG)
						->where('datetime_on', $_sign.'=', date('Y-m-d H:i:s'))
						->where('datetime_off', '>', date('Y-m-d H:i:s'))
						->where_is_valid(1)
						->order_by('datetime_on', $_order)
						->take($_n)
						->get();
						
			}

			if(!empty($list)) {

				$ul_options = array(
					'id' => $_id,
				);

				$li_options = array(
					'class' => $_class,
				);

				$view = LOAD_VIEW($_tpl);
				$view['list']		= $list;
				$view['ul_options']	= HTML::attributes($ul_options);
				$view['li_options']	= HTML::attributes($li_options);

				return $view;

			}

		}

	}


	/**
    * CRUMB Marker - Shows a BREADCRUMB style navigation menu
    *
	* [$CRUMB[{
	*	"home":"true",			=> OPTIONAL (default: true)	
	*	"separator":"<char>", 	=> OPTIONAL	
	*	"first":"false",		=> OPTIONAL (separator at start)
	*	"last":"false",			=> OPTIONAL (separator at the end)
	*	"label":""				=> OPTIONAL (default: cms::marker.crumb_here)
	*	"id":"<id>",			=> OPTIONAL (id of <ul>)
	*	"class":"<class>",		=> OPTIONAL (class of <ul>)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function CRUMB($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_home = true;
		if(isset($home) and !empty($home) and $home == 'false') $_home = false;

		$_separator = '';
		if(isset($separator) and !empty($separator)) $_separator = $separator;		

		$_first = false;
		if(isset($first) and !empty($first) and $first == 'true') $_first = true;

		$_last = false;
		if(isset($last) and !empty($last) and $last == 'true') $_last = true;

		$_label = LL('cms::marker.crumb_here', SITE_LANG)->get();
		if(isset($label) and !empty($label)) $_label = $label;

		$_id = null;
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'crumb';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'crumb';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_tpl)) {

			//CACHE DATA
			if(CACHE) {
				$menu = Cache::remember('menu_crumb_'.SITE_LANG, function() {
					return $menu = CmsPage::where_lang(SITE_LANG)->get();
				}, 1440);
			} else {
				$menu = CmsPage::where_lang(SITE_LANG)->get();
			}

			//EXPLODE SLUG

			$slugs = explode('/', SLUG_FULL);

			$crumbs = array();

			$tmp_slug = '';

			if($_home) {

				foreach ($menu as $item) {
					
					if($item->is_home == 1) {

						$crumbs['/'] = $item->name;

					}

				}

			}

			foreach ($slugs as $slug) {

				$tmp_slug = str_replace('//', '/', $tmp_slug . '/' . $slug);

				foreach ($menu as $item) {
					
					if($tmp_slug == $item->slug) {

						$crumbs[$tmp_slug] = $item->name;

					}

				}

			}

			if(count($slugs) > count($crumbs)) {

				$last_slug = SLUG_FULL;
				$crumbs[$last_slug] = $_label;

			}

		} else {

			$crumbs = array();

		}

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['crumbs']		= $crumbs;
		$view['separator'] 	= $_separator;
		$view['first'] 		= $_first;
		$view['last'] 		= $_last;
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * DISQUS Marker - Show DISQUS snippet
	*
	* [$DISQUS[{
	*	"class":"<class>",		=> OPTIONAL (class of disqus wrapper <div>)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function DISQUS($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_class = null;
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'disqus';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		Asset::container('footer')->add('disqus', 'bundles/cms/js/disqus.comment_count.js');

		$options = array(
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * DOWNLIST Marker - Show a list of download saved in Service / Download
	*
	* [$DOWNLIST[{
	*	"name":"<list name>",
	*	"full":"false",			=> OPTIONAL (if true, recursive on DOWNLOAD marker)
	*	"class":"<class>",		=> OPTIONAL (class of <ul>)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function DOWNLIST($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_name = '';
		if(isset($name) and !empty($name)) $_name = $name;

		$_full = false;
		if(isset($full) and !empty($full) and $full == 'true') $_full = true;

		$_class = null;
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'downlist';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//Get DB information

		if(!empty($_name)) {

			//CACHE DATA
			if(CACHE) {
				$list = Cache::remember('file_list_'.$_name.'_'.SITE_LANG, function() use ($_name) {
					return CmsDownload::with(array(
						'files',
						'files.filetexts' => function($query) {
					
						$query->where('lang', '=', SITE_LANG);

					}
					))->where_name($_name)->first();
				}, 1440);
			} else {
				$list = CmsDownload::with(array(
					'files',
					'files.filetexts' => function($query) {
					
						$query->where('lang', '=', SITE_LANG);

					}
					))->where_name($_name)->first();
			}

			//Load file lable and title
			if(!empty($list->files)) {

				$files = $list->files;

			} else {
				
				$files = array();

			}

		} else {

			$files = array();
			
		}

		$options = array(
			'id' => $_name,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['files'] 		= $files;
		$view['full']		= $_full;
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * DOWNLOAD Marker - Show a download link to file (not image)
	*
	* [$DOWNLOAD[{
	*	"file":"<filename>",
	*	"label":"label",		=> OPTIONAL (Overrides default label)
	*	"id":"<id>",			=> OPTIONAL (id of <a>)
	*	"class":"<class>",		=> OPTIONAL (class of <a> default: download)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function DOWNLOAD($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_file = '';
		if(isset($file) and !empty($file)) $_file = $file;

		$_label = '';
		if(isset($label) and !empty($label)) $_label = $label;

		$_id = null;
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'download';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'download';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//Get DB information

		if(!empty($_file)) {

			//CACHE DATA
			if(CACHE) {
				$file = Cache::remember('file_'.MEDIA_NOPOINT($_file).'_'.SITE_LANG, function() use ($_file) {
					return CmsFile::with(array('filetexts' => function($query) {
						
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_file)->first();
				}, 1440);
			} else {
				$file = CmsFile::with(array('filetexts' => function($query) {
							
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_file)->first();
			}

			//Get img dimension
			if(!empty($file)) {

				$full_path = $file->path;

			} else {

				$full_path = '';

			}

			//Load file lable and title
			if(!empty($file->filetexts)) {

				$title = $file->filetexts[0]->title;
				$lab = $file->filetexts[0]->label;

			} else {
				
				$title = '';
				$lab = $_file;

			}

			//Override label
			if(!empty($_label)) $lab = $_label;

		} else {

			$full_path = '';
			$lab = '';
			
		}

		$options = array(
			'id' => $_id,
			'class' => $_class,
			'title' => $title,
		);

		$view = LOAD_VIEW($_tpl);
		$view['path'] 		= $full_path;
		$view['label'] 		= $lab;
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * ELEMENT Marker - Replicates element content
    *
	* [$CONTENT[{
	*	"el":"<elem's id>"
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function ELEMENT($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_el = '';
		if(isset($el) and !empty($el)) $_el = $el;

		if(!empty($_el)) {

			//CACHE DATA
			if(CACHE) {

				$query = Cache::remember('page_element_'.$_el, function() {

					return $menu = CmsElement::find($_el);

				}, 1440);

			} else {

				$query = CmsElement::find($_el);

			}

			$txt = (!empty($query)) ? $query->text : '';

			return (strlen($txt)>0) ? self::decode($txt, array()) : $txt;

		}

	}


	/**
    * GALLERY Marker - Shows an image gallery saved in Service / Gallery
    *
	* [$GALLERY[{
	*	"name":"<gallery name>",
	*	"wm":"true | false",	=> OPTIONAL
	*	"class":"<class>",		=> OPTIONAL
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function GALLERY($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_name = '';
		if(isset($name) and !empty($name)) $_name = $name;

		$_wm = 'no';
		if(isset($wm) and !empty($wm) and $wm == 'true') $_wm = 'wm';

		$_class = 'inline';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'gallery';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//Get DB information

		if(!empty($_name)) {

			//CACHE DATA
			if(CACHE) {
				$gallery = Cache::remember('img_gallery_'.$_name, function() use ($_name) {
					return CmsGallery::with(array('files'))->where_name($_name)->first();
				}, 1440);
			} else {
				$gallery = CmsGallery::with(array('files'))->where_name($_name)->first();
			}

			//Load file lable and title
			if(!empty($gallery->files)) {

				$images = $gallery->files;
				$thumb = $gallery->thumb;

			} else {
				
				$images = array();
				$thumb = 'thumb';

			}

		} else {

			$images = array();
			$thumb = 'thumb';

		}

		$options = array(
			'id' => $_name,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['images'] 	= $images;
		$view['thumb']		= $thumb;
		$view['wm']			= ($_wm == 'wm') ? 'true' : 'no';
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * HOME Marker - It links to home
	*
	* [$HOME[{
	*	"path":"<url path>"		=> OPTIONAL (if empty, to home page)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function HOME($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_path = '';
		$_path = (isset($path) and !empty($path)) ?  URL::to($path) : URL::base();

		return $_path;

	}


	/**
    * IMAGE Marker - Show a on-the-fly resized image
	*
	* [$IMAGE[{
	*	"file":"<filename>",
	*	"type":"<type of crop>"	=> (default: resize | available: resize || crop)
	*	"w":"100",
	*	"h":"100",
	*	"x":"0",				=> OPTIONAL (if type crop, crop start x)
	*	"y":"0",				=> OPTIONAL (if type crop, crop start y)
	*	"wm":"true | false",	=> OPTIONAL
	*	"id":"<id>",			=> OPTIONAL
	*	"class":"<class>"		=> OPTIONAL
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function IMAGE($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_file = '';
		if(isset($file) and !empty($file)) $_file = $file;

		$_type = 'resize';
		if(isset($type) and !empty($type)) $_type = $type;

		$_w = 100;
		if(isset($w) and !empty($w)) $_w = $w;

		$_h = 100;
		if(isset($h) and !empty($h)) $_h = $h;

		$_x = 0;
		if(isset($x) and !empty($x)) $_x = $x;

		$_y = 0;
		if(isset($y) and !empty($y)) $_y = $y;

		$_wm = 'no';
		if(isset($wm) and !empty($wm) and $wm == 'true') $_wm = 'wm';

		$_id = null;
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = null;
		if(isset($class) and !empty($class)) $_class = $class;

		//Get DB information

		if(!empty($_file)) {

			//CACHE DATA
			if(CACHE) {
				$file = Cache::remember('img_'.MEDIA_NOPOINT($_file).'_'.SITE_LANG, function() use ($_file) {
					return CmsFile::with(array('filetexts' => function($query) {
						
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_file)->first();
				}, 1440);
			} else {
				$file = CmsFile::with(array('filetexts' => function($query) {
							
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_file)->first();
			}

			if($_type == 'resize') {

				//Get img dimension
				if(!empty($file)) {

					$dim = MEDIA_DIM($file->w, $file->h, $_w, $_h);

				} else {

					$dim['w'] = '';
					$dim['h'] = '';

				}

				$url = URL::to_action('cms::image@resize', array($dim['w'], $dim['h'], $_wm, $_file));

			}

			if($_type == 'crop') {

				$_filename = $file->name;
				$dim = array('w' => $_w, 'h' => $_h);
				$url = URL::to_action('cms::image@crop', array($_x, $_y, $_w, $_h, $_wm, $_filename));

			}

			//Load file alt
			if(!empty($file->filetexts)) {

				$alt = $file->filetexts[0]->alt;
				$caption = $file->filetexts[0]->caption;

			} else {
				
				$alt = '';
				$caption = '';

			}

		} else {

			$alt = '';
			$caption = '';
			$dim['w'] = '';
			$dim['h'] = '';

		}

		return HTML::image($url, $alt, array('width' => $dim['w'], 'height' => $dim['h'], 'id' => $_id, 'class' => $_class));

	}


	/**
    * LANG Marker - Shows a change lang menu
    *
	* [$LANG[{
	*	"separator":"<char>", 	=> OPTIONAL	
	*	"first":"false",		=> OPTIONAL (separator at start)
	*	"last":"false",			=> OPTIONAL (separator at the end)	
	*	"exclude":"<lang code-langcode-...>",	=> OPTIONAL (default: empty)
	*	"id":"<id>"				=> (lang id container | default: lang_menu)
	*	"class":"<class>",		=> OPTIONAL (default: lang)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function LANG($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		$_separator = '';
		if(isset($separator) and !empty($separator)) $_separator = $separator;

		$_first = false;
		if(isset($first) and !empty($first) and $first == 'true') $_first = true;

		$_last = false;
		if(isset($last) and !empty($last) and $last == 'true') $_last = true;

		$_exclude = '';
		if(isset($exclude) and !empty($exclude)) $_exclude = $exclude;

		$_id = 'lang_menu';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'lang';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'lang';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['langs'] 		= Config::get('cms::settings.langs');
		$view['separator'] 	= $_separator;
		$view['first'] 		= $_first;
		$view['last'] 		= $_last;
		$view['exclude'] 	= $_exclude;		
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * LINK Marker - Show a link to url
	*
	* [$LINK[{
	*	"url":"<url slug>",		=> (default: base url)
	*	"label":"<label>",		=> OPTIONAL (Overrides default label)
	*	"target":"<target>",	=> OPTIONAL (default: null)
	*	"id":"<id>",			=> OPTIONAL (id of <a>)
	*	"class":"<class>",		=> OPTIONAL (class of <a>)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function LINK($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_url = URL::base();
		if(isset($url) and !empty($url)) $_url = $url;

		$_label = $_url;
		if(isset($label) and !empty($label)) $_label = $label;

		$_target = null;
		if(isset($target) and !empty($target)) $_target = $target;

		$_id = null;
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = null;
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'link';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_url)) {

			$options = array(
				'id' => $_id,
				'class' => $_class,
				'target' => $_target,
			);

			$view = LOAD_VIEW($_tpl);
			$view['url'] 		= $_url;
			$view['label'] 		= $_label;		
			$view['options'] 	= HTML::attributes($options);

			return $view;

		}

	}

	/**
    * LOGIN Marker - Shows a login form
    *
	* [$LOGIN[{
	*	"id":"<id>"								=> OPTIONAL (form ID - default: login_form)
	*	"class":"<class>",						=> OPTIONAL (default: login)
	*	"tpl":"<tpl_name>"						=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function LOGIN($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		$_id = 'login_form';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'login';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'login';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['options'] = $options;

		return $view;

	}


	/**
    * LOGOUT Marker - Shows a logout button form
    * Based on Twitter Bootstrap bootstrap.js dropdown
    *
	* [$LOGOUT[{
	*	"id":"<id>"								=> OPTIONAL (form ID - default: logout)
	*	"class":"<class>",						=> OPTIONAL (default: logout)
	*	"tpl":"<tpl_name>"						=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function LOGOUT($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		$_id = 'logout';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'btn-group';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'logout';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['options'] = HTML::attributes($options);

		if(Auth::check()) return $view;

	}


	/**
    * MAP Marker - Shows a Google Maps map
    *
	* [$MAP[{
	*	"address":"<street address>",
	*	"zoom":"14"				=> Zoom level (default: 14)
	*	"w":"320",				=> (map width)
	*	"h":"240",				=> (map height)
	*	"type":"",				=> (map type: google.maps.MapTypeId.SATELLITE - default: empty)
	*	"id":"map"				=> (map id container | default: map)
	*	"class":"<class>",		=> OPTIONAL (default: map)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function MAP($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_address = '';
		if(isset($address) and !empty($address)) $_address = $address;

		$_address = str_replace("'", "\'", $_address);

		$_zoom = 14;
		if(isset($zoom) and !empty($zoom)) $_zoom = $zoom;

		$_w = 320;
		if(isset($w) and !empty($w)) $_w = $w;

		$_h = 240;
		if(isset($h) and !empty($h)) $_h = $h;

		$_type = '';
		if(isset($type) and !empty($type)) $_type = $type;

		$_id = 'map';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'map';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'map';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_address)) {

			//LOAD GOOLE MAPS LIBS
			Asset::container('header')->add('googlemaps', 'http://maps.google.com/maps/api/js?sensor=false', 'jquery');
			Asset::container('header')->add('gmap3', 'bundles/cms/js/jquery.gmap3.js', 'googlemaps');
			
			switch ($_type) {
				case 'satellite':
					$map_type = 'google.maps.MapTypeId.SATELLITE';
					break;
				
				default:
					$map_type = '';
					break;
			}

			$options = array(
				'id' => $_id,
				'class' => $_class,
			);

			$view = LOAD_VIEW($_tpl);
			$view['address'] 	= $_address;
			$view['zoom'] 		= $_zoom;
			$view['w']			= $_w;
			$view['h']			= $_h;
			$view['maptype']	= $map_type;
			$view['id']			= $_id;			
			$view['options'] 	= HTML::attributes($options);

			return $view;

		}

	}

	/**
    * MENU Marker - Show a menu saved in Service / Menu
	*
	* [$MENU[{
	*	"name":"<menu name>",
	*	"class":"<class>",		=> OPTIONAL (<ul> class)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function MENU($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_name = '';
		if(isset($name) and !empty($name)) $_name = $name;

		$_class = 'menu';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'menu';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//Get DB information

		if(!empty($_name)) {

			//CACHE DATA
			if(CACHE) {
				$m = Cache::remember('menu_'.$_name.'_'.SITE_LANG, function() use ($_name) {
					return CmsMenu::where_name($_name)
							->where_lang(SITE_LANG)
							->first();
				}, 5);
			} else {
				$m = CmsMenu::where_name($_name)
							->where_lang(SITE_LANG)
							->first();
			}


			if(!empty($m)) {

				$id = $m->id;

				$nested = (bool) $m->is_nested;

				$lower_parent = 0;

				if($nested) {

				 	$lower_parent = $m->parent_start;

				}

				//CACHE DATA
				if(CACHE) {
					$menu = Cache::remember('menu_pages_'.$_name.'_'.SITE_LANG, function() use ($_name, $nested, $lower_parent) {
						return CmsMenu::with(array('pages' => function($query) use ($nested, $lower_parent) {
						
							if($nested) $query->where('parent_id', '=', $lower_parent);

						}))
								->where_name($_name)
								->where_lang(SITE_LANG)
								->first();
					}, 5);
				} else {
					$menu = CmsMenu::with(array('pages' => function($query) use ($nested, $lower_parent) {
						
							if($nested) $query->where('parent_id', '=', $lower_parent);

						}))
								->where_name($_name)
								->where_lang(SITE_LANG)
								->first();
				}

				//Load file lable and title
				if(!empty($menu->pages)) {

					$pages = $menu->pages;
					$nested = (bool) $nested;

				} else {
					
					$pages = array();
					$nested = false;

				}

			} else {

				$id = null;

				$pages = array();
				$nested = false;

			}

		} else {

			$id = null;

			$pages = array();
			$nested = false;

		}

		$options = array(
			'id' => $_name,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['mid']	= $id;
		$view['pages'] 	= $pages;
		$view['nested']		= $nested;
		$view['options'] 	= HTML::attributes($options);

		return $view;

	}


	/**
    * MENU_CUSTOM Marker - Show a custom menu by template
	*
	* [$MENU_CUSTOM[{
	*	"id":"menu_custom"		=> (menu id container | default: menu_custom)
	*	"class":"<class>",		=> OPTIONAL (default: menu_custom)
	*	"tpl":"menu_custom"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function MENU_CUSTOM($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_id = 'menu_custom';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'menu_custom';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'menu_custom';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['options'] = HTML::attributes($options);

		return $view;

	}


	/**
    * MENU_SUB Marker - Shows a submenu linked to sub elements
	*
	* [$MENU_SUB[{
	*	"zone":"<elem's zone>"	=> (default: 1)
	*	"id":"menu_sub"			=> OPTIONAL (menu id container | default: menu_sub)
	*	"class":"<class>",		=> OPTIONAL (default: menu_sub)
	*	"tpl":"menu_sub"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function MENU_SUB($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_zone = 'ZONE1';
		if(isset($zone) and !empty($zone)) $_zone = 'ZONE'.$zone;

		$_id = 'menu_sub';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'menu_sub';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'menu_sub';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;


		//CACHE DATA
		if(CACHE) {
			
			$page = Cache::remember('menu_sub_'.md5(SLUG_FULL).'_'.SITE_LANG, function() use ($_zone) {
				
				return CmsPage::with(array('elements' => function($query) use ($_zone) {
					$query->where_zone($_zone)->where_is_valid(1);
				}))
						->where_slug(SLUG_FULL)
						->where_lang(SITE_LANG)
						->where_is_valid(1)
						->first();

			}, 5);

		} else {
			
			$page = CmsPage::with(array('elements' => function($query) use ($_zone) {
					$query->where_zone($_zone)->where_is_valid(1);
			}))
						->where_slug(SLUG_FULL)
						->where_lang(SITE_LANG)
						->where_is_valid(1)
						->first();

		}

		$elements = !empty($page->elements) ? $page->elements : array();

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['slug'] = SLUG_FULL;
		$view['elements'] = $elements;
		$view['options'] = HTML::attributes($options);

		return $view;

	}


	/**
    * PREVIEW Marker - Shows a list of contents from source
    *
	* [$PREVIEW[{
	*	"source":"<source label>",	=> (available: blogs, products...)
	*	"time":"<time label>",		=> (available: future, past, null - default: null)
	*	"n":"<n items per page>",	=> (default: config.design.pag)
	*	"id":"<id>",				=> OPTIONAL <ul> id
	*	"class":"<class>",			=> OPTIONAL <ul><li> class (default: list)
	*	"tpl":"<tpl_name>"			=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function PREVIEW($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_source = '';
		if(isset($source) and !empty($source)) $_source = $source;

		$_time = null;
		if(isset($time) and !empty($time)) $_time = $time;

		$_n = Config::get('cms::theme.site_pag');
		if(isset($n) and !empty($n)) $_n = $n;

		$_id = 'preview';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'list';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'preview';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_source)) {

			//SET MODEL RELATION

			$_source = $_source . '_preview';

			if(!is_null($_time)) $_source = $_source . '_' . $_time;

			//CACHE DATA
			if(CACHE) {

				$page = Cache::remember('page_'.Str::slug(SLUG_LAST, '_').'_'.SITE_LANG, function() use ($_source) {

					return CmsPage::where_lang(SITE_LANG)->where_slug(SLUG_FULL)->first();

				}, 5);

			} else {
				
				$page = CmsPage::where_lang(SITE_LANG)->where_slug(SLUG_FULL)->first();

			}

			if(!empty($page)) {

				//CACHE DATA
				if(CACHE) {

					$list = Cache::remember($_source.'_'.$page->id.'_'.Input::get('page', 1), function() use ($_source, $page, $_n) {
						return CmsPage::find($page->id)
								->$_source()
								->paginate($_n);
					}, 5);

				} else {				
					
					$list = CmsPage::find($page->id)
							->$_source()
							->paginate($_n);

				}

				$ul_options = array(
					'id' => $_id,
				);

				$li_options = array(
					'class' => $_class,
				);

				$view = LOAD_VIEW($_tpl);
				$view['page']		= $page;
				$view['ul_options']	= HTML::attributes($ul_options);
				$view['li_options']	= HTML::attributes($li_options);

				if(!empty($list)) {

					$view['list'] = $list;

				} else {

					$view['list'] = array();

				}

				return $view;

			}

		}

	}


	/**
    * PREVNEXT Marker - Loop paginator between pages or blog posts
    *
	* [$PREVNEXT[{
	*	"separator":""			=> OPTIONAL	
	*	"class":"<class>",		=> OPTIONAL (default: search)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function PREVNEXT($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		$_separator = '';
		if(isset($separator) and !empty($separator)) $_separator = $separator;

		$_class = '';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'prevnext';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;


		//CACHE DATA
		if(CACHE) {

			$this_page = Cache::remember('page_'.Str::slug(SLUG_LAST, '_').'_'.SITE_LANG, function() {

				return CmsPage::where_lang(SITE_LANG)
						->where_slug(SLUG_FULL)
						->first();

			}, 5);

		} else {
			
			$this_page = CmsPage::where_lang(SITE_LANG)
						->where_slug(SLUG_FULL)
						->first();

		}

		// GET THIS PAGE
		$this_order = $this_page->order_id;
		$this_id = $this_page->id;
		$this_parent = $this_page->parent_id;


		// GET ALL PAGES
		if(CACHE) {

			$pages = Cache::remember('page_'.$this_parent.'_'.SITE_LANG, function() use ($this_parent) {

				return CmsPage::where_lang(SITE_LANG)
						->where_parent_id($this_parent)
						->order_by('order_id', 'asc')
						->order_by('id', 'asc')
						->get();

			}, 5);

		} else {
			
			$pages = CmsPage::where_lang(SITE_LANG)
						->where_parent_id($this_parent)
						->order_by('order_id', 'asc')
						->order_by('id', 'asc')
						->get();

		}

		// CHECK BY ORDER_ID
		$order = ($this_order == Config::get('cms::settings.order')) ? false : true;

		
		// ITERATE PAGES TO FIND ARRAY KEY
		foreach ($pages as $key => $page) {			
			if($page->id == $this_id) $page_index = $key;
		}

		$page_count = count($pages);

		// GET PREV
		if(array_key_exists($page_index - 1, $pages)) {
			
			$prev_slug = $pages[$page_index - 1]->slug;

		} else {

			$prev_slug = $pages[$page_count - 1]->slug;

		}		

		// GET NEXT
		if(array_key_exists($page_index + 1, $pages)) {
			
			$next_slug = $pages[$page_index + 1]->slug;

		} else {

			$next_slug = $pages[0]->slug;

		}


		$options = array(
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['separator'] 	= $_separator;
		$view['prev_slug']	= $prev_slug;
		$view['next_slug']	= $next_slug;
		$view['options']	= HTML::attributes($options);

		return $view;

	}


	/**
    * SEARCH Marker - Shows a search form
    *
	* [$SEARCH[{
	*	"source":"<source-source>",				=> (available: pages, blogs - default: pages)
	*	"id":"<id>"								=> OPTIONAL (form ID - default: search_form)
	*	"class":"<class>",						=> OPTIONAL (default: search)
	*	"tpl":"<tpl_name>"						=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function SEARCH($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		$_source = 'pages';
		if(isset($source) and !empty($source)) $_source = $source;

		$_id = 'search_form';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'search';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'search';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		$options = array(
			'id' => $_id,
			'class' => $_class,
		);

		$view = LOAD_VIEW($_tpl);
		$view['source'] = $_source;
		$view['options'] = $options;

		return $view;

	}


	/**
    * SOCIAL Marker - Shows a social toolbar
    *
	* [$SOCIAL[{
	*	"what":"facebook",	=> (available: facebook-twitter-linkedin-google-follow)
	*	"user":"[username]" => OPTIONAL (MANDATORY if 'what' includes 'follow or linkedin')
	*	"class":"<class>",		=> OPTIONAL (default: social)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function SOCIAL($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_what = 'facebook';
		if(isset($what) and !empty($what)) $_what = trim(str_replace(' ', '', $what));

		$_user = '';
		if(isset($user) and !empty($user)) $_user = $user;

		$_class = 'addthis_toolbox addthis_default_style social';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'social';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_what)) {

			//LOAD ADDTHIS LIBS
			Asset::container('header')->add('addthis', 'http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50d155e0673e5c43', 'jquery');
			
			$services = explode("-", $_what);

			$options = array(
				'class' => $_class,
			);

			$view = LOAD_VIEW($_tpl);
			$view['services'] 	= $services;
			$view['user']		= $_user;		
			$view['options'] 	= HTML::attributes($options);

			return $view;

		}

	}


	/**
    * TEST Marker - Test sandbox
    *
	* [$TEST[]]
    *
    */
	public static function TEST($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_tpl = 'test';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		$view = View::make('cms::theme.'.THEME.'.partials.markers.'.$_tpl);

		return $view;

	}
	

	/**
    * THUMB Marker - Show a pre-config or on-the-fly resized thumb image linked to original
	*
	* [$THUMB[{
	*	"file":"<filename>",
	*	"type":"<type of crop>"			=> (default: resize | available: resize || crop)
	*	"thumb":"<type of thumb>",		=> (default: thumb | available as mapped in theme.php thumb array)
	*	"path":"img_path | !img_path"	=> (point to full image path or $caption || $alt slug - default: img_path)
	*	"caption":"false"				=> (default: false)
	*	"w":"100",						=> OPTIONAL (overrides thumb)
	*	"h":"100",						=> OPTIONAL (overrides thumb)
	*	"x":"0",						=> OPTIONAL (if type crop, crop start x)
	*	"y":"0",						=> OPTIONAL (if type crop, crop start y)
	*	"wm":"true | false",			=> OPTIONAL
	*	"id":"<id>",					=> OPTIONAL (id of <a>)
	*	"class":"<class>",				=> OPTIONAL
	*	"tpl":"<tpl_name>"				=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function THUMB($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_file = '';
		if(isset($file) and !empty($file)) $_file = $file;

		$_type = 'resize';
		if(isset($type) and !empty($type)) $_type = $type;

		$_thumb = 'thumb';
		if(isset($thumb) and !empty($thumb)) $_thumb = $thumb;

		$_path = 'img_path';
		if(isset($path) and !empty($path)) $_path = $path;

		$_caption = false;
		if(isset($caption) and !empty($caption) and $caption == 'true') $_caption = true;

		$_w = '';
		if(isset($w) and !empty($w)) $_w = $w;

		$_h = '';
		if(isset($h) and !empty($h)) $_h = $h;

		$_x = 0;
		if(isset($x) and !empty($x)) $_x = $x;

		$_y = 0;
		if(isset($y) and !empty($y)) $_y = $y;

		$_wm = 'no';
		if(isset($wm) and !empty($wm) and $wm == 'true') $_wm = 'wm';

		$_id = null;
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = null;
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'thumb';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		//Get DB information

		if(!empty($_file)) {

			//CACHE DATA
			if(CACHE) {
				$file = Cache::remember('img_'.MEDIA_NOPOINT($_file).'_'.SITE_LANG, function() use ($_file) {
					return CmsFile::with(array('filetexts' => function($query) {
						
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_file)->first();
				}, 1440);
			} else {
				$file = CmsFile::with(array('filetexts' => function($query) {
							
							$query->where('lang', '=', SITE_LANG);

						}))->where_name($_file)->first();
			}

			//Get img dimension
			if(!empty($file) and !empty($_type)) {

				if($_path == 'img_path') {

					//LOAD FANCYBOX LIBS
					Asset::container('header')->add('fancyboxcss', 'bundles/cms/css/fancybox.css', 'site_css');
					Asset::container('footer')->add('fancybox', 'bundles/cms/js/jquery.fancybox.js', 'jquery_lib');
					Asset::container('footer')->add('thumb', 'js/markers/thumb.js', 'site_js');

				}

				if($_type == 'resize') {

					// GET W OR H
					if(!empty($_w) or !empty($_h)) {

						$_filename = $file->name;
						$dim = MEDIA_DIM($file->w, $file->h, $_w, $_h);
						$url = URL::to_action('cms::image@resize', array($dim['w'], $dim['h'], $_wm, $_filename));

					// GET THUMB, DEFAULT THUMB IF NONE
					} else {

						$_filename = MEDIA_NAME($_file, Config::get('cms::theme.thumb.'.$_thumb.'.suffix'));
						$dim['w'] = Config::get('cms::theme.thumb.'.$_thumb.'.width');
						$dim['h'] = Config::get('cms::theme.thumb.'.$_thumb.'.height');
						$url = MEDIA_NAME($file->path, Config::get('cms::theme.thumb.'.$_thumb.'.suffix'));

					}

				}

				if($_type == 'crop') {

					$_filename = $file->name;
					$dim = array('w' => $_w, 'h' => $_h);
					$url = URL::to_action('cms::image@crop', array($_x, $_y, $_w, $_h, $_wm, $_filename));

				}				

				$full_path = $file->path;

				// Apply watermark if required
				if($_wm == 'wm') $full_path = URL::to_action('cms::image@resize', array($file->w, $file->h, $_wm, $file->name));

			} else {

				$_filename = '';
				$dim['w'] = '';
				$dim['h'] = '';

			}

			//Load file alt and title
			if(!empty($file->filetexts)) {

				$title = $file->filetexts[0]->title;
				$alt = $file->filetexts[0]->alt;
				$caption = $file->filetexts[0]->caption;

			} else {
				
				$title = '';
				$alt = '';
				$caption = '';

			}

		} else {

			$full_path = '';
			$title = '';
			$alt = '';
			$caption = '';
			$url = '';
			$dim['w'] = '';
			$dim['h'] = '';

		}

		$img = HTML::image($url, $alt, array('id' => $_id, 'class' => $_class));

		$options = array(
			'id' => $_id,
			'class' => $_class,
			'title' => $title
		);

		if($_path == 'img_path') {

			$options['rel'] = 'fancybox';

		} else {

			$full_path = SLUG_FULL . '/' . Str::slug(($alt != '') ? $alt : $caption);

		}

		$view = LOAD_VIEW($_tpl);
		$view['path'] 			= $full_path;
		$view['img'] 			= $img;
		$view['options'] 		= HTML::attributes($options);		
		$view['caption'] 		= $_caption;
		$view['caption_text'] 	= $caption;

		return $view;

	}



	/**
    * TRANSLATION Marker - Shows a translated portion of text in current language
    *
	* [$TRANSLATION[{
	*	"key":"<text>",
	*	"style":"<lower | upper | capital | allcapital>",	=> OPTIONAL (default: lower)
	*	"to":"<lang code>"		=> OPTIONAL (default: settings.language)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function TRANSLATION($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_key = '';
		if(isset($key) and !empty($key)) $_key = trim(str_replace('  ', ' ', $key));

		$_style = '';
		if(isset($style) and !empty($style)) $_style = $style;

		$_to = SITE_LANG;
		if(isset($to) and !empty($to)) $_to = $to;

		//Get DB information

		if(!empty($_key)) {

			//CACHE DATA
			if(CACHE) {
				$trans = Cache::remember('trans_'.md5($_key).'_'.$_to, function() use ($_key, $_to) {
					return CmsTranslation::where_lang_from(LANG)
											->where_lang_to($_to)
											->where_word($_key)
											->first();
				}, 1440);
			} else {
				$trans = CmsTranslation::where_lang_from(LANG)
											->where_lang_to($_to)
											->where_word($_key)
											->first();
			}

			//Load file lable and title
			if(!empty($trans)) {

				$value = $trans->value;

				return CmsUtility::string_style($value, $style = $_style);

			} else {
				
				return $_key;

			}

		} else {

			return $_key;

		}

		return $_key;

	}


	/**
    * TWEETS Marker - Shows Twitter ticker
    *
	* [$TWEETS[{
	*	"user":"[username]" 	=> (Twitter username)
	*	"n":"<n items >",		=> (default: 5)
	*	"id":"tweet-list"		=> (list id | default: tweet-list)
	*	"class":"<class>",		=> OPTIONAL (default: social)
	*	"tpl":"<tpl_name>"		=> OPTIONAL (in /partials/markers)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function TWEETS($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_user = '';
		if(isset($user) and !empty($user)) $_user = $user;

		$_n = 5;
		if(isset($n) and !empty($n)) $_n = $n;

		$_id = 'tweet-list';
		if(isset($id) and !empty($id)) $_id = $id;

		$_class = 'tweets';
		if(isset($class) and !empty($class)) $_class = $class;

		$_tpl = 'tweets';
		if(isset($tpl) and !empty($tpl)) $_tpl = $tpl;

		if(!empty($_user)) {

			$options = array(
				'id' => $_id,
				'class' => $_class,
			);

			$_feed = "http://search.twitter.com/search.atom?q=from:" . $_user . "&rpp=" . $_n;
			$twitter_feed = file_get_contents($_feed);
			$twitter_feed = simplexml_load_string($twitter_feed);

			$view = LOAD_VIEW($_tpl);
			$view['user'] 		= $_user;
			$view['tweets'] 	= $twitter_feed;
			$view['options'] 	= HTML::attributes($options);

			return $view;

		}

	}


	/**
    * VIDEO Marker - Shows video embed from Youtube, Screenr, Vimeo or local hosted
    *
	* [$VIDEO[{
	*	"code":"<video code>",	=> (video code)
	*	"site":"local",			=> (available: youtube || screenr || vimeo || local)
	*	"w":"420",				=> (video width)
	*	"h":"315",				=> (video height)
	*	"skin":"<skin>",		=> OPTIONAL (flowplayer skin override)
	*	"class":"<class>",		=> OPTIONAL (default: video)
	* }]]
    *
    * @param  array
    * @return string
    */
	public static function VIDEO($vars = array())
	{

		//Get variables from array $vars
		if( ! empty($vars)) extract($vars);

		//Bind variables

		$_code = '';
		if(isset($code) and !empty($code)) $_code = $code;

		// NAME IS CODE ALIAS
		if(isset($name) and !empty($name)) $_code = $name;

		$_site = 'local';
		if(isset($site) and !empty($site)) $_site = $site;

		$_w = '';
		if(isset($w) and !empty($w)) $_w = $w;

		$_h = '';
		if(isset($h) and !empty($h)) $_h = $h;

		$_skin = '';
		if(isset($skin) and !empty($skin)) $_skin = $skin;

		$_class = 'video';
		if(isset($class) and !empty($class)) $_class = $class;

		if(!empty($_code)) {

			$_media_url = '';

			// LOAD FLOWPLAYER
			if($_site === 'local') {

				$_skin_player = (!empty($_skin)) ? $_skin : CONF('cms::settings.flowplayer','skin');

				Asset::container('header')->add('flowplayercss', 'bundles/cms/flowplayer/'.$_skin_player.'.css', 'site_css');
				Asset::container('footer')->add('flowplayer', 'bundles/cms/js/flowplayer.min.js', 'jquery_lib');

				$_site = 'video';
				$_type = File::extension($_code);
				$_class = CONF('cms::settings.flowplayer','options') . ' ' . $_class;
				$_media_url = MEDIA_URL($_code);

			} else {

				// SET DEFAULT VIDEO SIZE

				if($_w == '') $_w = 420;
				if($_h == '') $_h = 315;

			}

			$options = array(
				'class' => $_class,
			);

			$view = View::make('cms::theme.'.THEME.'.partials.markers.'.$_site);;
			$view['url']		= $_media_url;
			$view['code']		= $_code;
			$view['w']			= $_w;
			$view['h']			= $_h;
			$view['options'] 	= HTML::attributes($options);

			return $view;

		}

	}


	

}
