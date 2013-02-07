<?php

class CmsUtility {


	/**
	* Size of path content
	*
	* @param  string $path
	* @return int
	*/
	public static function pathsize($app, $path, $type = '')
	{

		$files = glob($app . $path . '*');

		$size = 0;

		foreach($files as $file) {

			$size = $size + filesize($file);			

		}

		if(empty($type)) return $size;

		return MEDIA_SIZE($size, $type);		

	}


	/**
	* Size of media based on Db
	*
	* @param  string $path
	* @return int
	*/
	public static function mediasize($image = 1, $type = '')
	{

		if(CACHE) {

			$size = Cache::remember('size_'.$image, function() use ($image) {

				$sum = CmsFile::where_is_image($image)->sum('size');

				return $sum;

			}, 60);

		} else {

			$size = CmsFile::where_is_image($image)->sum('size');

		}

		if(empty($type)) return $size;

		return  MEDIA_SIZE($size, $type);

	}


	/**
	* Start a new backup process
	*
	* @return string $file_name
	*/
	public static function db_backup()
	{
	  
		$type_db = Config::get('database.default');
		$connections = Config::get('database.connections');

		switch($type_db) {
			
		  case 'sqlite':
			  
			$file_name = $connections[$type_db]['database'] . '.' . $connections[$type_db]['driver'];
			
			break;
		  
		  case 'mysql':
			  
			$link = mysql_connect($connections[$type_db]['host'],$connections[$type_db]['username'],$connections[$type_db]['password']);
			mysql_select_db($connections[$type_db]['database'],$link);
			
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result))
			{
			  $tables[] = $row[0];
			}
			
			//Set time now
			$now = date('Y-m-d-H-i-s');
			
			//File header
			$return ="### DB BACKUP: " . $connections[$type_db]['database'] . " at " . $now . " ###\n\n\n";
			
			//cycle through
			foreach($tables as $table)
			{
			  $result = mysql_query('SELECT * FROM '.$table);
			  $num_fields = mysql_num_fields($result);
			  
			  $return.= 'DROP TABLE IF EXISTS '.$table.';';
			  $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			  $return.= "\n\n".$row2[1].";\n\n";
			  
			  for ($i = 0; $i < $num_fields; $i++) 
			  {
				while($row = mysql_fetch_row($result))
				{
				  $return.= 'INSERT INTO '.$table.' VALUES(';
				  for($j=0; $j<$num_fields; $j++) 
				  {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = preg_replace("#\n#i","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				  }
				  $return.= ");\n";
				}
			  }
			  $return.="\n\n\n";
			}
			
			//save file
			$file_name = 'db-backup-'.$now.'.sql';
			
			$handle = fopen(path('storage') . 'database/' . $file_name, 'w+');
			fwrite($handle, utf8_encode($return));
			fclose($handle);
				
		}

		return $file_name;

	}


	/**
	* Get page URL segment and last URL segment
	*
	* @return string
	* @return array
	*/
	public static function url_segments($key = '')
	{

		$tmp_url = URL::current();		

		$tmp_url = str_replace(Config::get('application.url'), '', $tmp_url);

		$segments = explode('/', $tmp_url);

		//CHECK IF LANG IS IN URL
		if(array_key_exists($segments[1], Config::get('cms::settings.langs'))) {

			//SAVE TO SESSION
			Session::put('SITE_LANG', $segments[1]);

			//REMOVE LANG FROM TMP_URL
			$tmp_url = str_replace('/' . $segments[1], '', $tmp_url);

		}
		
		// CHECK PREVIEW REQUEST
		$is_preview = false;

		if('/'.end($segments) === Config::get('cms::settings.preview')) {

			$is_preview = true;

			$remove_preview = array_pop($segments);

			//REMOVE PREVIEW FROM TMP_URL
			$tmp_url = str_replace(Config::get('cms::settings.preview'), '', $tmp_url);

		}

		$last_segment = '/' . end($segments);

		$first_segment = '/' . str_replace($last_segment, '', $tmp_url);		

		$slugs = array(
			'first' => str_replace('//', '/', $first_segment),
			'last' => str_replace('//', '/', $last_segment),
			'full' => str_replace('//', '/', $first_segment . $last_segment),
			'preview' => $is_preview
		);

		if(array_key_exists($key, $slugs)) {
			
			return str_replace('//', '/', $slugs[$key]);

		}

		return $slugs;

	}


	/**
	* Get slug of homepage
	*
	* @return string
	*/
	public static function home_page()
	{
		
		if(CACHE) {

			$obj = Cache::remember('home_page_'.SITE_LANG, function() {

				return CmsPage::where_is_home(1)->where_lang(SITE_LANG)->first();

			}, 1440);

		} else {

			$obj = CmsPage::where_is_home(1)->where_lang(SITE_LANG)->first();
			
		}

		if(empty($obj)) return '/';

		return $obj->slug;
	}


	/**
	* Get slug parsed
	*
	* @return string
	*/
	public static function parse_slug($slug)
	{

		if($slug == SITE_HOMEPAGE) {

			return URL::base();
			
		}

		return URL::to($slug);

	}


	/**
	* Get text lower, UPPER, Capital, AllCapital
	*
	* @return string
	*/
	public static function string_style($str, $style = '')
	{

		if(!empty($str)) {

			switch ($style) {
				
				case 'lower':
					
					return strtolower($str);
					break;
				
				case 'upper':
					
					return strtoupper($str);
					break;
				
				case 'capital':
					
					return ucfirst($str);
					break;
				
				case 'allcapital':
					
					return ucwords($str);
					break;

				default:
					return $str;
			}

		}

		return '';

	}


	/**
	* Set active class to active link
	*
	* @return string
	*/
	public static function link_active($slug)
	{
		if (SLUG_FULL == $slug) return HTML::attributes(array('class' => 'active'));
	}

	/**
	* Set active class to active link in menu
	*
	* @return string
	*/
	public static function link_menu_active($slug)
	{
		if (SLUG_FULL == '/' and $slug == SITE_HOMEPAGE) return HTML::attributes(array('class' => 'active'));
		if (substr_count(SLUG_FULL.'/', $slug.'/') > 0) return HTML::attributes(array('class' => 'active'));
	}

	/**
	* Set active class to active language
	*
	* @return string
	*/
	public static function link_lang($code)
	{
		if ($code == SITE_LANG) return HTML::attributes(array('class' => 'active'));
	}

	/**
	* Get relative time string
	*
	* @param  string
	* @return string
	*/
	public static function relative_time($time_string)
	{

		// Time arrays
		$intervalNames = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
		$intervalSeconds = array( 1, 60, 3600, 86400, 604800, 2630880, 31570560);

		// Start time
		$time = 'just now';
		$secondsPassed = time() - strtotime($time_string);

		if ($secondsPassed>0) {
			// see what interval are we in
			for($j = count($intervalSeconds)-1; ($j >= 0); $j--) {
				$crtIntervalName = $intervalNames[$j];
				$crtInterval = $intervalSeconds[$j];

				if ($secondsPassed >= $crtInterval) {
					$value = floor($secondsPassed / $crtInterval);

					if ($value > 1)	$crtIntervalName .= 's';

					$time = $value . ' ' . LL('cms::time.'.$crtIntervalName, LANG) . ' ' . LL('cms::time.ago', LANG);

					break;
				}
			}
		}

		return $time;

	}


}
