<?php

/*
|--------------------------------------------------------------------------
| CMS theme settings
|--------------------------------------------------------------------------|
*/

$THEME_SETTINGS = array(

	/*
	|--------------------------------------------------------------------------
	| Theme project name (short name)
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
			'container' => 'footer',
			'after' => null,

		),

		'site_js' => array(

			'path' => 'js/site.js',
			'container' => 'footer',
			'after' => 'jquery_lib',

		),

		'bootstrap_css' => array(

			'path' => 'css/bootstrap.min.css',
			'container' => 'header',
			'after' => null,

		),

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

	'template' => 'default',

	/*
	|--------------------------------------------------------------------------
	| SITE HEADER DEFINITION | Do not remove 'default'
	|--------------------------------------------------------------------------|
	*/

	'header' => array(
		
		'default' => 'Default',

	),

	/*
	|--------------------------------------------------------------------------
	| SITE FOOTER DEFINITION | Do not remove 'default'
	|--------------------------------------------------------------------------|
	*/

	'footer' => array(
		
		'default' => 'Default',
		
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
	| ELEMENT CLASS NAME
	|--------------------------------------------------------------------------|
	*/

	'ele_class' => 'element',

	/*
	|--------------------------------------------------------------------------
	| SITE PAGINATION ITEMS
	|--------------------------------------------------------------------------|
	*/

	'pag' => 20,

	/*
	|--------------------------------------------------------------------------
	| SITE SEO DEFAULT SETTINGS
	|--------------------------------------------------------------------------|
	*/

	'title' => 'Finestre per arredare',
	
	'keyw' => 'keyword1, keyword2, keyword3',

	'descr' => 'This is the default description',

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


);
