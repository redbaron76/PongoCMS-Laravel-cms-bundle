<?php


/* APPLICATION ROUTES */

// SET SEGMENTS LIMIT
Router::$segments = 10;

//APPLICATION ROUTES

Route::get('(.*)', array('as' => 'master', 'before' => 'init', function($url) {

	return CmsRender::page();

}));

//APPLICATION CONTROLLERS

Route::controller(Controller::detect());


//APPLICATION FILTER FRONTEND

Route::filter('init', function()
{

	//SAVE SESSION CREDENTIAL
	if(Auth::check() and is_numeric(AUTHORID)) {

		Session::put('USERID', Auth::user()->id);
		Session::put('USERNAME', Auth::user()->username);
		Session::put('EMAIL', Auth::user()->email);		
		Session::put('ROLEID', Auth::user()->role_id);
		Session::put('ROLE', Auth::user()->role_level);
		Session::put('USERLANG', Auth::user()->lang);

	} else {

		Session::put('USERID', 0);
		Session::put('USERNAME', '');
		Session::put('EMAIL', '');		
		Session::put('ROLEID', 0);
		Session::put('ROLE', 0);
		Session::put('USERLANG', Config::get('application.language'));

	}

	//LOAD SEGMENTS
	$segment = CmsUtility::url_segments();

	//SEGMENTS SLUG CONSTANT	
	define('SLUG_FULL', $segment['full']);
	define('SLUG_FIRST', $segment['first']);
	define('SLUG_LAST', $segment['last']);
	define('SLUG_BACK', $segment['first']);
	// BOOLEAN
	define('SLUG_PREVIEW', $segment['preview']);

	//GLOBAL CONSTANT
	define('SITE_URL', Config::get('application.url'));
	define('SITE_USERID', Session::get('USERID', 0));
	define('SITE_USERNAME', Session::get('USERNAME', ''));
	define('SITE_EMAIL', Session::get('EMAIL', ''));
	define('SITE_ROLEID', Session::get('ROLEID', 0));
	define('SITE_ROLE', Session::get('ROLE', 0));
	define('SITE_LANG', Session::get('SITE_LANG', Config::get('application.language')));
	define('SITE_HOMEPAGE', CmsUtility::home_page());

	define('THEME', Config::get('cms::settings.theme'));

	//SET LOCALE

	setlocale(LC_ALL, Config::get('cms::settings.locale.'.SITE_LANG), Config::get('cms::settings.locale.'.SITE_LANG).'.utf8');

});






/* CMS INTERFACE ROUTES */


//ROUTE /cms
Route::get('(:bundle)', 'cms::dashboard@index');

//ROUTE /cms/logout
Route::get('(:bundle)/logout', 'cms::login@logout');

//ROUTE /cms/lang/(:any)
Route::get('(:bundle)/lang/(:any)', 'cms::dashboard@lang');

//TRANSLATION /cms/translation/lang/(:any)
Route::get('(:bundle)/translation/(:any)', 'cms::translation@index');

//ROUTE /cms/page/sitemap
Route::get('(:bundle)/page/sitemap', 'cms::page@sitemap');

//ROUTE /cms/page|blog/(:any?)
Route::get('(:bundle)/blog/(:any?)', 'cms::blog@index');
Route::get('(:bundle)/banner/(:any?)', 'cms::banner@index');
Route::get('(:bundle)/menu/(:any?)', 'cms::menu@index');
Route::get('(:bundle)/page/(:any?)', 'cms::page@index');
Route::get('(:bundle)/tag/(:any?)', 'cms::tag@index');

//ELEMENT CUSTOM ROUTE
Route::get('(:bundle)/page/element/new/(:num)', 'cms::page@new_element');
Route::get('(:bundle)/page/element/edit/(:num)/(:num)', 'cms::page@edit_element');

//DELETE CACHE
Route::get('(:bundle)/backup/db', 'cms::dashboard@db_backup');
Route::get('(:bundle)/delete/cache/(:any?)', 'cms::dashboard@delete_cache');


//AJAX ROUTES

//PAGES GET PARENT PATH (cms.js)
Route::post('(:bundle)/ajax/get/page/parent/paths', 'cms::ajax_page@parent_paths');

//PAGES GET PARENT ZONES (cms.js)
Route::post('(:bundle)/ajax/get/page/parent/zones', 'cms::ajax_page@parent_zones');

//LAYOUT PREVIEW (cms.js)
Route::post('(:bundle)/ajax/page/layout', 'cms::ajax_page@preview_layout');

//UPLOAD ROUTE (cms.js)
Route::post('(:bundle)/ajax/upload/media', 'cms::ajax_page@upload_media');

//MEDIA LIST (cms.js)
Route::post('(:bundle)/ajax/media/list', 'cms::ajax_page@media_list');

//FILE TEXT (cms.js)
Route::post('(:bundle)/ajax/file/text/lang', 'cms::ajax_file@file_text_lang');

//GET POST TAGS
Route::get('(:bundle)/ajax/get/tags', 'cms::ajax_tag@tags');
Route::post('(:bundle)/ajax/populate/tags/(:any)', 'cms::ajax_tag@populate_tags');
Route::post('(:bundle)/ajax/add/tags', 'cms::ajax_tag@add_tags');

//SORTING ROUTE (cms.js)
Route::post('(:bundle)/ajax/page/list/order', 'cms::ajax_page@order_list');
Route::post('(:bundle)/ajax/page/element/order', 'cms::ajax_page@order_element');
Route::post('(:bundle)/ajax/page/subpage/order', 'cms::ajax_page@order_subpage');
Route::post('(:bundle)/ajax/menu/page/order', 'cms::ajax_menu@order_menu');
Route::post('(:bundle)/ajax/download/file/order', 'cms::ajax_download@order_download');
Route::post('(:bundle)/ajax/gallery/file/order', 'cms::ajax_gallery@order_gallery');
Route::post('(:bundle)/ajax/banner/file/order', 'cms::ajax_banner@order_banner');

//TRANSLATION DELETE
Route::post('(:bundle)/ajax/translation/delete', 'cms::ajax_translation@delete_translation');

//SEARCH ROUTES
Route::any('(:bundle)/blog/search', 'cms::search@search_blog');
Route::any('(:bundle)/file/search', 'cms::search@search_file');
Route::any('(:bundle)/page/search', 'cms::search@search_page');
Route::any('(:bundle)/role/search', 'cms::search@search_role');
Route::any('(:bundle)/tag/search', 'cms::search@search_tag');
Route::any('(:bundle)/user/search', 'cms::search@search_user');

// CALENDAR SEARCH
Route::any('(:bundle)/calendar/search', 'cms::search@search_calendar');


//CMS CONTROLLERS

Route::controller(Controller::detect('cms'));


//CMS FILTER BACKEND

Route::filter('cms_no_auth', function()
{
	//FORCE LOGOUT IF NOT ROLE
	if (Auth::check() and (ROLE < Config::get('cms::settings.roles.editor'))) return Redirect::to_action('cms::login');
	if (Auth::guest()) return Redirect::to_action('cms::login');

});


Route::filter('cms_is_auth', function()
{	
	if (Auth::check() and (URL::current() != URL::to_action('cms::logout')) and (ROLE >= Config::get('cms::settings.roles.editor'))) return Redirect::to_action('cms::dashboard');
});


Route::filter('save_session_credentials', function()
{
	//Save session credentials
	if(Auth::check()) {
		Session::put('USERNAME', Auth::user()->username);
		Session::put('AUTHORID', Auth::user()->id);
		Session::put('ROLE', Auth::user()->role_level);
		Session::put('CMSLANG', Auth::user()->lang);
		Session::put('LANG', Config::get('cms::settings.language'));
		Session::put('EDITOR', Auth::user()->editor);
	}
});

//CMS EVENT LISTEN

/*Event::listen('laravel.started: cms', function() {
    //echo path('bundle');
});*/

/*Event::listen('laravel.query', function($sql, $bindings, $time) {
	echo '<br><br><br><pre>'.$sql.var_dump($bindings).'</pre>';
});*/

