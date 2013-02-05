<?php

class Cms_Setup_Task {

	//SETUP CMS BUNDLE
	public function run($arguments = array())
	{
		
		$env = (!empty($arguments)) ? ' --env='.$arguments[0] : '';

		//INSTALL SWIFTMAILER BUNDLE
		$swiftmailer = shell_exec('php artisan bundle:install swiftmailer');

		//COPY CONTROLLERS
		$controller_path = path('bundle').'cms'.DS.'controllers'.DS.'_app_controllers';
		$app_controller_path = path('app').'controllers';
		File::cpdir($controller_path, $app_controller_path, false);

		//COPY BUNDLE ASSET
		$result = shell_exec('php artisan bundle:publish cms'.$env);

		//COPY THEME ASSETS
		$asset_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.Config::get('cms::settings.theme').DS.'public';
		$public_path = path('public');
		File::cpdir($asset_path, $public_path, false);

		//CREATE DATA UPLOAD FOLDER
		$data_path = path('public').Config::get('cms::settings.data');
		if(! file_exists($data_path)) $crate = mkdir($data_path);

		//SET CMSAUTH TO APPLICATION CONFIG
		$new_config = array(
			'driver' => array('eloquent', 'cmsauth'),
			'username' => array('email', 'username'),
			'model' => array('User', 'CmsUser'),
		);

		$app_file = path('app').'config'.DS.'auth'.EXT;

		$config = File::get($app_file);

		foreach ($new_config as $key => $value) {
			$config = str_replace("'".$key."' => '".$value[0]."',", "'".$key."' => '{$value[1]}',", $config);
		}

		File::put($app_file, $config);

		//SET DB PREFIX
		$db_path = path('app').'config'.DS.'database'.EXT;

		$db_conf = File::get($db_path);
		$db_conf = str_replace("'prefix'   => '',", "'prefix'   => 'pongo_',", $db_conf);
		
		File::put($db_path, $db_conf);

		//SET SESSION DRIVER
		$session_path = path('app').'config'.DS.'session'.EXT;

		$session_conf = File::get($session_path);
		$session_conf = str_replace("'driver' => 'cookie',", "'driver' => 'file',", $session_conf);
		
		File::put($session_path, $session_conf);

		//DISABLE ROUTES AND FILTERS

		$backup_path = path('base').'_backup';
		mkdir($backup_path);

		//COPY routes.php TO _backup/routes_original.php
		$routes_file = path('app').'routes'.EXT;
		rename($routes_file, $backup_path.DS.'routes_original'.EXT);

		//MOVE /controllers/routes.php TO /application
		rename($app_controller_path.DS.'routes'.EXT, path('app').'routes'.EXT);

		//MOVE start_swiftmailer_bundle.php TO /cms/bundles/swiftmailer
		$sw_path = path('bundle').'swiftmailer';
		if(file_exists($sw_path)) {			
			rename($app_controller_path.DS.'start_swiftmailer_bundle'.EXT, $sw_path.DS.'start'.EXT);
			echo $swiftmailer;
		}

		//DELETE HOME CONTROLLER
		$home_file = path('app').'controllers'.DS.'home'.EXT;
		if(file_exists($home_file)) unlink($home_file);

		//MOVE NEW bundles.php to /application
		rename($app_controller_path.DS.'bundles'.EXT, path('app').'bundles'.EXT);

		//INSTALL MIGRATION
		$result = shell_exec('php artisan migrate:install'.$env);

		//MIGRATE TABLES
		$result = shell_exec('php artisan migrate cms'.$env);

		//INSERT DEFAULT DATA
		$default_data = path('bundle').'cms'.DS.'default_content';
		$row_data = File::get($default_data);

		$data = explode("');", $row_data);

		array_pop($data);

		foreach ($data as $q) {
			
			$query = $q . "')";

			//MySQL ADAPTOR
			if(Config::get('database.default') != 'sqlite') {

				//NULL to 0
				$query = str_replace(',NULL,', ',0,', $query);

				//"table_name" to 'table_name'
				$query = str_replace('INTO "', 'INTO ', $query);
				$query = str_replace('" VALUES', ' VALUES', $query);

			}

			DB::query($query);

		}

		echo PHP_EOL;
		echo 'Setup complete!'.PHP_EOL;
		echo PHP_EOL;
		echo 'Visit frontend site: '.Config::get('application.url').PHP_EOL;
		echo 'or login to '.Config::get('application.url').'/cms (admin/admin)'.PHP_EOL;		

	}


	//MIGRATE THEME ASSET TO PUBLIC
	public function theme($arguments = array())
	{

		$current_theme = Config::get('cms::settings.theme');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		//CHECK THEME FOLDER EXISTENCE

		$theme_settings = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme.DS.'theme'.EXT;

		if(file_exists($theme_settings)) {

			//SET NEW THEME NAME IN SETTINGS
			$theme_path = path('bundle').'cms'.DS.'config'.DS.'settings'.EXT;
			$theme_conf = File::get($theme_path);
			$theme_conf = str_replace("'theme' => '".$current_theme."',", "'theme' => '{$theme}',", $theme_conf);
			File::put($theme_path, $theme_conf);

			//COPY ASSETS
			$asset_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme.DS.'public';

			if(file_exists($asset_path)) {

				//EMPTY BLADE COMPILED FILES
				$storage_path = path('storage').'views';

				$files = glob($storage_path . '/*');

				foreach($files as $file) {

					if($file != '.gitignore') unlink($file);
					
				}

				//EMPTY PUBLIC PATH
				$dirs = array('css', 'img', 'js');

				$public_path = path('public');

				foreach ($dirs as $dir) {
					
					$files = glob($public_path . $dir . '/*');

					foreach($files as $file) {

						if(is_dir($file)) {

							self::rrmdir($file);

						} else {

							if($file != '.gitignore') unlink($file);

						}

					}

				}

				//COPY PUBLIC ASSETTS
				$asset_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme.DS.'public';
				File::cpdir($asset_path, $public_path, false);

				echo 'Theme ['.$theme.'] ready!'.PHP_EOL;

			} else {

				echo 'Theme ['.$theme.'] doesn\'t exists!'.PHP_EOL;

			}

		} else {

			echo '['.$theme.'] theme folder doesn\'t exists!'.PHP_EOL;

		}

	}


	//RECURSIVE RMDIR
	private static function rrmdir($dir)
	{
		if (is_dir($dir)) {
			
			$objects = scandir($dir);  
			
			foreach ($objects as $object) {

				if ($object != '.' && $object != '..') {

					if (filetype($dir.DS.$object) == 'dir') {

						self::rrmdir($dir.DS.$object);

					} else {

						unlink($dir.DS.$object);

					}
				}

			}

			reset($objects);

			rmdir($dir);
			
		}  
	}

}
