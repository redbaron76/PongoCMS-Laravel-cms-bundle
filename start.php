<?php

//ACTIVATE CMSAUTH

Auth::extend('cmsauth', function()
{
	return new Cms_Auth;
});


//FORCE SESSION TO BE LOADED - AVOID TASKS ERROR

if (Request::cli() and Config::get('session.driver') !== '')
{
	Session::load();
}


//CMS COMPOSER

View::composer('cms::interface.layouts.default', function($view)
{
	
	Asset::container('header')->add('app_js', Config::get('application.url').'/site/js');
	Asset::container('header')->add('jquery', 'bundles/cms/js/jquery.min.js', 'app_js');	
	Asset::container('footer')->add('cms', 'bundles/cms/js/cms.js', 'jquery');
	Asset::container('footer')->add('bootstrap', 'bundles/cms/js/bootstrap.min.js', 'cms');
	Asset::container('header')->add('bootstrapcss', 'bundles/cms/css/bootstrap.css');
	Asset::container('header')->add('main', 'bundles/cms/css/cms.css', 'bootstrapcss');

	//NOTY
	Asset::container('header')->add('notyjs', 'bundles/cms/js/jquery.noty.js', 'jquery');
	Asset::container('header')->add('notycss', 'bundles/cms/css/jquery.noty.css', 'bootstrapcss');
	Asset::container('header')->add('notytwitcss', 'bundles/cms/css/noty_theme_twitter.css', 'notycss');

	//HEADER_DATA IS SET IN RESTFUL CONTROLLERS

	if(!isset($view->header_data)) $view->header_data = array('title' => Config::get('cms::settings.name'));

	$topbar = (Auth::check() and is_numeric(AUTHORID) and (ROLE >= Config::get('cms::settings.roles.editor'))) ?
	'cms::interface.partials.navigation.logged' : 'cms::interface.partials.navigation.login';

	$view->menu = View::make('cms::interface.partials.menu')->nest('topbar', $topbar, array(
			'sections' => Config::get('cms::settings.sections'),
			'interface' => Config::get('cms::settings.interface'),
		)
	);

	//TOP_DATA IS SET IN RESTFUL CONTROLLERS

	if(!isset($view->top_data)) $view->top_data = array('search' => false);
	
	//CONTENT IS SET IN RESTFUL CONTROLLERS

	if(!isset($view->content)) $view->content = '';

	$view->footer = View::make('cms::interface.partials.footer');
	
});

//CMS CUSTOM VALIDATIONS

Validator::register('alpha_slug', function($attribute, $value, $parameters)
{
	return preg_match('/^([-a-z0-9-])+$/i', $value);
});

Validator::register('unique_slug', function($attribute, $value, $parameters)
{
	
	//CHECK UNIQUENESS OF SLUG IN LANGUAGE

	$table = $parameters[0];
	$page_lang = $parameters[1];
	$page_parent_slug = $parameters[2];
	$page_id = $parameters[3];
	$page_slug = $value;
	
	$full_slug = $page_parent_slug.'/'.$page_slug;

	$query = DB::table($table)
						->where('slug', '=', $full_slug)
						->where('lang', '=', $page_lang)
						->where('id', '<>', $page_id);

	return $query->count() == 0;

});

Validator::register('unique_lang', function($attribute, $value, $parameters)
{
	
	//CHECK UNIQUENESS OF VALUE IN LANGUAGE

	$page_id = $parameters[0];
	$page_lang = $parameters[1];
	$table = $parameters[2];

	if(isset($parameters[3])) $attribute = $parameters[3];

	$query = DB::table($table)
						->where($attribute, '=', $value)
						->where('lang', '=', $page_lang)
						->where('id', '<>', $page_id);

	return $query->count() == 0;

});

Validator::register('unique_element_page', function($attribute, $value, $parameters)
{
	
	//CHECK UNIQUENESS OF ELEMENT NAME IN A PAGE

	$page_id = $parameters[0];
	$element_id = $parameters[1];

	if(isset($parameters[2])) $attribute = $parameters[2];

	$elements = CmsPage::find($page_id)->elements()
				->where('cmselement_id','<>',$element_id)
				->where($attribute, '=', $value)
				->count();

	return $elements == 0;

});

Validator::register('unique_file', function($attribute, $value, $parameters)
{
	
	//CHECK UNIQUENESS OF FILE NAME ON DISK

	$file_name = $parameters[0];
	$path = $parameters[1];

	// CHECK DB NAME
	$query = CmsFile::where_name($file_name);

	return !file_exists(path('public') . $path . $file_name) and $query->count() == 0;

});

Validator::register('unique_filename', function($attribute, $value, $parameters)
{
	
	//CHECK UNIQUENESS OF FILE NAME

	$ext = $parameters[0];
	if(isset($parameters[1])) $attribute = $parameters[1];

	$query = DB::table('files')->where($attribute, '=', $value.'.'.$ext);

	return $query->count() == 0;

});

Validator::register('valid_datetime', function($attribute, $value, $parameters)
{

	//match the format of the date

	if(!empty($value)) {

		$d = DateTime::createFromFormat(GET_DATETIME(), $value);

		return (!$d) ? false : true;
		
	}

	return false;


});

Validator::register('valid_date', function($attribute, $value, $parameters)
{

	//match the format of the date

	if(!empty($value)) {

		$d = DateTime::createFromFormat(GET_DATETIME(false), $value);

		return (!$d) ? false : true;
		
	}

	return false;


});

// BLADE MODIFICATION

Blade::extend(function($value) {
    return preg_replace('/\{\{([^\-].+?)\}\}/s', '<?php echo $1; ?>', $value);
});


//BUNDLE CONSTANTS

define('USERNAME', Session::get('USERNAME'));
define('AUTHORID', Session::get('AUTHORID'));
define('ROLE', Session::get('ROLE', 0));
define('LANG', Session::get('LANG', Config::get('cms::settings.language')));
define('EDITOR', Session::get('EDITOR', 'ckeditor'));
define('CMSLANG', Session::get('CMSLANG', Config::get('cms::settings.language')));
define('CACHE', Config::get('cms::settings.cache_engine'));
define('BASE', Config::get('application.url'));


//BUNDLE REQUIREMENTS

require path('bundle').'cms/libraries/helpers'.EXT;
