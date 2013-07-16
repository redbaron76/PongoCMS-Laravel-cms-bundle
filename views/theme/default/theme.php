<?php

/*
|--------------------------------------------------------------------------
| CMS theme settings
|--------------------------------------------------------------------------|
*/

$THEME_SETTINGS = array(

	/*
	|--------------------------------------------------------------------------
	| Theme project name (short name) - Set DISQUS site name like this!
	|--------------------------------------------------------------------------|
	*/

	'project_name' => 'PongoCMS',

	/*
	|--------------------------------------------------------------------------
	| APPLICATION ASSETS | in /PUBLIC
	|--------------------------------------------------------------------------|
	*/

	'asset' => array(

		'jquery_lib' => array(

			'path' => 'js/jquery.min.js',
			'container' => 'header',
			'after' => null,

		),

		'analytics_lib' => array(

			'path' => 'js/jquery.analytics.js',
			'container' => 'footer',
			'after' => 'jquery_lib',

		),

		'site_js' => array(

			'path' => 'js/site.js',
			'container' => 'footer',
			'after' => 'analytics_lib',

		),

		'bootstrap_css' => array(

			'path' => 'css/bootstrap.min.css',
			'container' => 'header',
			'after' => null,

		),

		// Change key name accordingly with CKEDITOR config.js

		'site_css' => array(

			'path' => 'css/site.css',
			'container' => 'header',
			'after' => 'bootstrap_css',

		),

	),

	/*
	|--------------------------------------------------------------------------
	| SITE DEFAULT TEMPLATE | Do not remove 'default'
	|--------------------------------------------------------------------------|
	*/

	'template' => array(
		
		'default' => 'Default Template',

	),

	/*
	|--------------------------------------------------------------------------
	| SITE HEADER DEFINITION | Do not remove 'default'
	|--------------------------------------------------------------------------|
	*/

	'header' => array(
		
		'default' => 'Default Header',

	),

	/*
	|--------------------------------------------------------------------------
	| SITE FOOTER DEFINITION | Do not remove 'default'
	|--------------------------------------------------------------------------|
	*/

	'footer' => array(
		
		'default' => 'Default Footer',
		
	),

	/*
	|--------------------------------------------------------------------------
	| SITE LAYOUT DEFINITION
	| DO NOT REMOVE 'default' !!
	|--------------------------------------------------------------------------|
	*/

	'layout' => array(
		
		'default' => 'One Column',
		'home' => 'Two Columns',
		
	),

	/*
	|--------------------------------------------------------------------------
	| SITE LAYOUT => ZONE DEFINITION | Keep sync layout $key -> layout_$key
	| DO NOT REMOVE 'layout_default' !!
	|--------------------------------------------------------------------------|
	*/	

	'layout_default' => array(

		'ZONE1' => 'Full column',

	),

	'layout_home' => array(
		
		'ZONE1' => 'Left column',
		'ZONE2' => 'Right column',

	),

	/*
	|--------------------------------------------------------------------------
	| $SEARCH marker results ZONE in layout | Map to existing zone
	|--------------------------------------------------------------------------|
	*/

	'search_zone'		=> 'ZONE1',

	/*
	|--------------------------------------------------------------------------
	| $SEARCH marker results view | in: /partials
	|--------------------------------------------------------------------------|
	*/

	'search_results' 	=> 'search_results',

	/*
	|--------------------------------------------------------------------------
	| $LOGIN marker form ZONE in layout | Map to existing zone
	|--------------------------------------------------------------------------|
	*/

	'login_zone'		=> 'ZONE1',

	/*
	|--------------------------------------------------------------------------
	| SIGNUP view form | Same ZONE of login_zone | in: /partials
	|--------------------------------------------------------------------------|
	*/

	'signup_form' 		=> 'signup_form',

	/*
	|--------------------------------------------------------------------------
	| SITE SAMPLES | Map blueprints in /theme/sample | file_name => descr
	|--------------------------------------------------------------------------|
	*/	

	'sample' => array(

		'page' => 'Page content sample',
		'blog_post' => 'Blog post sample',

	),

	/*
	|--------------------------------------------------------------------------
	| ELEMENT CLASS NAME
	|--------------------------------------------------------------------------|
	*/

	'ele_class' => 'element',

	/*
	|--------------------------------------------------------------------------
	| SITE PAGINATION ITEMS
	|--------------------------------------------------------------------------|
	*/

	'site_pag' => 10,

	/*
	|--------------------------------------------------------------------------
	| SITE SEO DEFAULT SETTINGS
	|--------------------------------------------------------------------------|
	*/

	'title' => 'Welcome to PongoCMS',
	
	'keyw' => 'keyword1, keyword2, keyword3',

	'descr' => 'This is the default description',

	/*
	|--------------------------------------------------------------------------
	| SITE TITLE STYLE: '', lower, upper, capital, allcapital
	|--------------------------------------------------------------------------|
	*/

	'title_style' => 'allcapital',

	/*
	|--------------------------------------------------------------------------
	| Theme thumb generator on upload
	|--------------------------------------------------------------------------|
	*/

	'thumb' => array(
						
						//CUSTOM THUMB CREATOR

						/*'mini' => array(
											'width'	=> 320,
											'height' => 200,
											'suffix' => '_mini',
											'method' => 'resize'
										),*/

						'quad' => array(
											'width'	=> 100,
											'height' => 100,
											'suffix' => '_quad',
											'method' => 'adaptiveResize'
										),

						//NEEDED FOR CMS LIST PREVIEW - DO NOT REMOVE!!
						'thumb' => array(
											'width'	=> 50,
											'height' => 50,
											'suffix' => '_thumb',
											'method' => 'adaptiveResize'
										),
					),

	/*
	|--------------------------------------------------------------------------
	| Theme watermark properties
	|--------------------------------------------------------------------------|
	*/

	'watermark' => array(
							'path' => 'bundles/cms/img/watermark.png',
							'horizontal' => 'right',
							'vertical' => 'bottom',
						),

	/*
	|--------------------------------------------------------------------------
	| Application Email Sender | NEED SwiftMailer bundle!!
	|--------------------------------------------------------------------------
	*/

	'email' => array(
		
		'email@address.tld' => 'PongoCMS'

	),

	'email_data' => array(
		
		'signup_subject' => 'Welcome to PongoCMS',

	),


);
