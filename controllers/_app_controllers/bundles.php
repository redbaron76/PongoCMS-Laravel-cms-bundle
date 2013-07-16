<?php

return array(

	'cms' => array(
		'autoloads' => array(
			'map' => array(

				//CMS CORE
				'Cms_Base_Controller'       => '(:bundle)/controllers/cms_base.php',
				'Cms_Searchbase_Controller' => '(:bundle)/controllers/cms_searchbase.php',
				'Cms_Auth'                  => '(:bundle)/auth/cms_auth.php',

				//PLUG-INS
				'PhpThumbFactory'           => '(:bundle)/libraries/thumb/ThumbLib.inc.php',

			),
			'directories' => array(
				'(:bundle)/libraries',
				'(:bundle)/models',
			)
		),
		'handles' => 'cms',
		'auto' => true,
	),

	'swiftmailer' => array(
		'auto'=>true
	)

);