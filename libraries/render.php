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

			}))
					->where_slug($base)
					->where_is_valid(1)
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
	public static function page()
	{

		//Cerco pagina con lang = SITE_LANG e is_homepage = 1

		if(SLUG_FULL == '/') {		// HOMEPAGE

			$page = CmsPage::with(array('elements' => function($query) {

				$query->where_is_valid(1);

			}))
					->where_lang(SITE_LANG)
					->where_is_valid(1)
					->first();

			if(empty($page)) {

				return Response::error('404');
				
			}

		//Cerco altre pagine con lang = SITE_LANG e slug = SLUG_FULL

		} else {					// ALTRE PAGINE

			//Verifico che slug non sia solo lang

			if(array_key_exists(str_replace('/', '', SLUG_FULL), Config::get('cms::settings.langs'))) {

				//Redirect al cambio lingua
				return Redirect::to_action('site@lang', array(str_replace('/', '', SLUG_FULL)));

			}

			//Carico la pagina

			$page = self::page_base(SLUG_FULL);

			//Verifico che esista la pagina

			if(empty($page)) {

				//Se non esiste, cerco con SLUG_FIRST e salvo SLUG LAST

				$page = self::page_base(SLUG_FIRST);

				//Verifico che esista la pagina
				
				//Non esiste, ERROR 404

				if(empty($page)) {

					return Response::error('404');

				//Pagina esiste ed è ZOOM di un EXTRA

				} else {

					//Ottengo il model da caricare
					
					switch(getExtra($page->extra_id)) {
		
						case 'blogs':
							$model = CmsBlog::with(array('blogrels'));
							break;

						//case 'products':
						//	$model = 'CmsProduct';
						//	break;

					}

					//Carico lo zoom se $extra esiste

					if(isset($model)) {

						$extra = $model
							->where_lang(SITE_LANG)
							->where_slug(SLUG_LAST)
							->where_is_valid(1)
							->first();

					}

				}

			}			

		}	


		//Bind $elements / $extra to ZONE

		if( ! empty($page)) {

			//Verifico se SITE_ROLE < access_level -> to_login

			if(SITE_ROLE < $page->access_level) {
				return Redirect::to_action('user@login')
				->with('back_url', URL::current());
			}

			//Imposto page_layout da DB o default se non settato
			$page_layout = $page->layout;
			if(empty($page->layout)) $page_layout = 'default';

			//Carico layouts da config.design
			$arr_layout = Config::get('cms::theme.layout_'.$page_layout);

			//Carico template
			$layout = View::make('cms::theme.'.THEME.'.layouts.'.$page_layout);

			//Verifico che esista il layout
			if( ! empty($arr_layout)) {

				//Bindo le zone come vuote per evitare errori
				foreach ($arr_layout as $key => $value) {

					$layout[strtoupper($key)] = '';

				}

			}

			//Bindo contenuti alle variabili di layout

			if( ! empty($page)) {

				//Bindo elementi alla ZONA nel layout pagina

				if( ! empty($page->elements)) {

					//Creo array di zone
					$zone = array();
					
					foreach($page->elements as $item) {
						
						$tmp_text = '<div id="'.$item->name.'" class="'.Config::get('cms::theme.ele_class').'">';
						$tmp_text .= Marker::decode($item->text);
						$tmp_text .= '</div>';

						$zone[$item->zone][] = $tmp_text;
					}

					//Bindo text del pageitem a ZONE che diventa variabile nel layout
					foreach($page->elements as $item) {							
						$layout[strtoupper($item->zone)] = trim(implode("\n", $zone[$item->zone]));
					} 

				}

				//Bindo $extra alla ZONA layout pagine se presente

				if( ! empty($extra)) {

					//EXTRA VIEW

					$extra_what = CONF('cms::settings.extra_id', $page->extra_id);

					$tmp_text = View::make('cms::theme.'.THEME.'.partials.preview.'.$extra_what);
					$tmp_text['text'] = $extra;

					//Bindo text del pageitem a ZONE che diventa variabile nel layout						
					$layout[strtoupper($extra->zone)] = trim(implode("\n", array($tmp_text)));

				}

			}

		} else {

			//Pagina non trovata, layout vuoto

			$layout = '';

		}

		//Set default title
		$title = ( ! empty($page->title)) ? $page->title : $page->name;
		//Set $extra title
		$title = (isset($extra)) ? $extra->name : $title;
		$title = (! empty($extra->title)) ? $extra->title : $title;

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

		//Set default header, footer, layout se non settati
		$header = ( ! empty($pager->header)) ? $page->header : 'default';
		$footer = ( ! empty($pager->footer)) ? $page->footer : 'default';


		//Prepare html buffer

		$html = View::make('cms::theme.'.THEME.'.templates.'.TEMPLATE)
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
