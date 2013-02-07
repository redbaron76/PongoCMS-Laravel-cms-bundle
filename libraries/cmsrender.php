<?php

class CmsRender {
	

	/**
	* Render theme assets
	*
	* @return string
	*/
	public static function asset()
	{

		$asset = Config::get('cms::theme.asset');

		if(is_array($asset)) {

			foreach ($asset as $name => $value) {

				//FALLBACK ON BUNDLE IF NOT FOUND
				$path = file_exists(path('public').$value['path']) ? $value['path'] : 'bundles'.Config::get('cms::settings.url').'/'.$value['path'];
				
				if(substr_count($value['path'], 'http') > 0) $path = $value['path'];
				
				Asset::container($value['container'])->add($name, $path, $value['after']);

			}

		}

	}


	/**
	* Get page base from slug
	*
	* @return string
	*/
	public static function page_base($base = SLUG_FULL)
	{

		$pages = CmsPage::with(array('elements' => function($query) {

				$query->where_is_valid(1);

		}))	->where_slug($base)
			->where_is_valid(VALID($base))
			->get();

		if(!empty($pages)) {

			if(count($pages) == 1) {

				$pag = $pages[0];

			} else {

				$lang = Config::get('application.language');

				foreach ($pages as $key => $page) {
					
					if($page->lang == $lang) $pag = $pages[$key];

					if($page->lang == SITE_LANG) $pag = $pages[$key];

				}

			}

			if($pag->lang != SITE_LANG) {

				Session::put('SITE_LANG', $pag->lang);

			}

			return $pag;

		}

	}


	/**
	* Render page template with all variables
	*
	* @return string
	*/
	public static function page($slug = null, $inject = array())
	{

		// CHECK $slug IS NOT NULL

		$SLUG_FULL = is_null($slug) ? SLUG_FULL : $slug;

		// Page with lang = SITE_LANG and is_homepage = 1

		if($SLUG_FULL === '/') {		// HOMEPAGE

			$page = CmsPage::with(array('elements' => function($query) {

				$query->where_is_valid(1);

			}))
					->where_lang(SITE_LANG)
					->where_is_valid(VALID())
					->first();

			if(empty($page)) {

				return Response::error('404');
				
			}

		// More pages with lang = SITE_LANG and slug = SLUG_FULL

		} else {					// MORE PAGES

			// Check slug is not lang

			if(array_key_exists(str_replace('/', '', $SLUG_FULL), Config::get('cms::settings.langs'))) {

				//Redirect al cambio lingua
				return Redirect::to_action('site@lang', array(str_replace('/', '', $SLUG_FULL)));

			}

			// Get page

			$page = self::page_base($SLUG_FULL);

			// Check page exists

			if(empty($page)) {

				// If not exists, look at SLUG_FIRST and save SLUG_LAST

				$page = self::page_base(SLUG_FIRST);

				// Check page exists
				
				// Not exists

				if(empty($page)) {
					
					return Response::error('404');

				// It exists and it is a ZOOM of EXTRA

				} else {

					// Get Model to load
					
					switch(getExtra($page->extra_id)) {
		
						case 'blogs':
							$model = CmsBlog::with(array('blogrels'));
							break;

						//case 'products':
						//	$model = 'CmsProduct';
						//	break;

					}

					// Load ZOOM if $extra exists

					if(isset($model)) {

						$extra = $model
							->where_lang(SITE_LANG)
							->where_slug(SLUG_LAST)
							->where_is_valid(VALID())
							->first();

					}

				}

			}			

		}	


		//Bind $elements / $extra to ZONE

		if( ! empty($page)) {

			// Check if SITE_ROLE < access_level -> to_login

			if(SITE_ROLE < $page->access_level) {
				return Redirect::to_action('site@login')
				->with('back_url', $SLUG_FULL);
			}

			// Set page_layout from DB or default if not set
			$page_layout = $page->layout;
			if(empty($page->layout)) $page_layout = 'default';

			// Get layouts from config.design
			$arr_layout = Config::get('cms::theme.layout_'.$page_layout);

			// Load template
			$layout = View::make('cms::theme.'.THEME.'.layouts.'.$page_layout);

			// Bind page name
			$layout['NAME'] = $page->name;

			// Check layout exists
			if( ! empty($arr_layout)) {

				// Bind zones as empty to avoid errors
				foreach ($arr_layout as $key => $value) {

					$layout[strtoupper($key)] = '';

				}

			}

			// Bind contents to layout variables

			if( ! empty($page)) {

				// Bind elements to $ZONE in page layout

				if( ! empty($page->elements)) {

					// Create zone array
					$zone = array();
					
					foreach($page->elements as $item) {
						
						$tmp_text = '<div id="'.$item->name.'" class="'.Config::get('cms::theme.ele_class').'">';
						$tmp_text .= Marker::decode($item->text);
						$tmp_text .= '</div>';

						$zone[$item->zone][] = $tmp_text;
					}

					// INJECT EXTERNAL ELEMENT INTO ZONE
					if(!empty($inject)) {

						$zone[$inject['zone']][0] = $inject['view'];

					}

					// Bind pageitem text to ZONE which become layout variable
					foreach($page->elements as $item) {							
						$layout[strtoupper($item->zone)] = trim(implode("\n", $zone[$item->zone]));
					} 

				} 

				// If no element present, move to first available child in order

				else {

					$new_page = CmsPage::where_lang(SITE_LANG)
					->where_parent_id($page->id)
					->where_is_valid(1)
					->order_by('order_id', 'asc')
					->first();

					if( ! empty($new_page)) return Redirect::to($new_page->slug);

				}

				// Bind $extra to layout ZONE if present

				if( ! empty($extra)) {

					//EXTRA VIEW ZOOM

					$extra_what = CONF('cms::settings.extra_id', $page->extra_id);

					$tmp_text = View::make('cms::theme.'.THEME.'.partials.details.'.$extra_what);
					$tmp_text['text'] = $extra;

					// Bind extra name
					$layout['NAME'] = $extra->name;

					// Bind pageitem text to ZONE which become layout variable
					$layout[strtoupper($extra->zone)] = trim(implode("\n", array($tmp_text)));

				}

			}

		} else {

			// Page not found, empty layout

			$layout = '';

		}

		//Set default title
		$title = ( ! empty($page->title)) ? $page->title : $page->name;
		//Set $extra title
		$title = (isset($extra)) ? $extra->name : $title;
		$title = (! empty($extra->title)) ? $extra->title : $title;

		$title = CmsUtility::string_style($title, Config::get('cms::theme.title_style'));

		// Add preview string to title
		if(SLUG_PREVIEW) $title = LL('cms::title.preview_title', CMSLANG) . $title;

		//Set default keyw
		$keyw = ( ! empty($page->keyw)) ? $page->keyw : Config::get('cms::theme.keyw');
		//Set $extra keyw
		$keyw = (isset($extra)) ? $extra->keyw : $keyw;
		$keyw = (! empty($extra->keyw)) ? $extra->keyw : $keyw;

		//Set default descr
		$descr = ( ! empty($page->descr)) ? $page->descr : Config::get('cms::theme.descr');
		//Set $extra descr
		$descr = (isset($extra)) ? $extra->descr : $descr;
		$descr = (! empty($extra->descr)) ? $extra->descr : $descr;

		//Set default template, header, footer, layout se non settati
		$template = ( ! empty($page->template)) ? $page->template : 'default';
		$header = ( ! empty($page->header)) ? $page->header : 'default';
		$footer = ( ! empty($page->footer)) ? $page->footer : 'default';



		//APPLICATION COMPOSER

		View::composer('cms::theme.'.THEME.'.templates.'.$template, function($view)
		{
			
			CmsRender::asset();

			//BASE JS
			Asset::container('header')->add('base_js', Config::get('application.url').'/site/js');

			if(!isset($view->title)) $view->title = Config::get('cms::theme.title');

			if(!isset($view->descr)) $view->descr = Config::get('cms::theme.descr');

			if(!isset($view->keyw)) $view->keyw = Config::get('cms::theme.keyw');

			if(!isset($view->header)) $view->header = '';

			if(!isset($view->layout)) $view->layout = '';

			if(!isset($view->footer)) $view->footer = '';
			
		});


		//Prepare html buffer

		$html = View::make('cms::theme.'.THEME.'.templates.'.$template)
		->with('title', $title)
		->with('keyw', $keyw)
		->with('descr', $descr)
		->nest('header', 'cms::theme.'.THEME.'.partials.header_'.$header)
		->with('layout', $layout)
		->nest('footer', 'cms::theme.'.THEME.'.partials.footer_'.$footer);

		
		//Output buffer

		self::clean_code($html);

	}


	//CLEAN HTML

	/**
	* Return clean or normal html code
	*
	* @return string
	*/
	public static function clean_code($html)
	{
		
		if(Config::get('cms::settings.clear_engine')) {
			
			$clean = new CleanOutput();
			$clean->process($html);
			$clean->show();
			$clean->reset();

		} else {

			return $html;

		}
		
	}




}
