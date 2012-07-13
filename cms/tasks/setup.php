<?php

class Cms_Setup_Task {

	//SETUP CMS BUNDLE
	public function run($arguments = array())
	{
		
		//COPY CONTROLLERS
		$controller_path = path('bundle').'cms/controllers/_app_controllers';
		$app_controller_path = path('app').'controllers';
		File::cpdir($controller_path, $app_controller_path, false);

		//COPY BUNDLE ASSET
		$result = shell_exec('php artisan bundle:publish cms');

		//COPY THEME ASSETS
		$asset_path = path('bundle').'cms/views/theme/'.Config::get('cms::theme.name').'/public';
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

		$app_file = path('app').'config/auth'.EXT;

		$config = File::get($app_file);

		foreach ($new_config as $key => $value) {
			$config = str_replace("'".$key."' => '".$value[0]."',", "'".$key."' => '{$value[1]}',", $config);
		}

		File::put($app_file, $config);

		//SET DB PREFIX
		$db_path = path('app').'config/database'.EXT;

		$db_conf = File::get($db_path);
		$db_conf = str_replace("'prefix'   => '',", "'prefix'   => 'pongo_',", $db_conf);
		
		File::put($db_path, $db_conf);

		//DISABLE ROUTES AND FILTERS
		$routes_file = path('app').'routes'.EXT;

		$routes = File::get($routes_file);

		$routes = str_replace('/*', '', $routes);
		$routes = str_replace('*/', '', $routes);
		$routes = str_replace('|', '//|', $routes);

		$routes = str_replace('Route::', '/*Route::', $routes);
		$routes = str_replace('Event::', '/*Event::', $routes);
		$routes = str_replace('});', '});*/', $routes);

		File::put($routes_file, $routes);

		//DELETE HOME CONTROLLER
		$home_file = path('app').'controllers/home'.EXT;
		if(file_exists($home_file)) unlink($home_file);

		//INSTALL MIGRATION
		$result = shell_exec('php artisan migrate:install');

		//MIGRATE TABLES
		$result = shell_exec('php artisan migrate cms');

		//INSERT DEFAULT DATA
		$default_data = path('bundle').'cms/default_content';
		$row_data = File::get($default_data);

		$data = explode("');", $row_data);

		array_pop($data);

		foreach ($data as $q) {
			
			$query = $q . "')";

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

		$current_theme = Config::get('cms::theme.name');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		//SET NEW THEME NAME
		$theme_path = path('bundle').'cms/config/theme'.EXT;
		$theme_conf = File::get($theme_path);
		$theme_conf = str_replace("'name' => '".$current_theme."',", "'name' => '{$theme}',", $theme_conf);
		File::put($theme_path, $theme_conf);

		//COPY ASSETS
		$asset_path = path('bundle').'cms/views/theme/'.$theme.'/public';

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
			$asset_path = path('bundle').'cms/views/theme/'.$theme.'/public';
			File::cpdir($asset_path, $public_path, false);

			echo 'Theme '.$theme.' ready!'.PHP_EOL;

		} else {

			echo 'Theme '.$theme.' doesn\'t exists!'.PHP_EOL;

		}

	}


	//RECURSIVE RMDIR
	private static function rrmdir($dir)
	{
		if (is_dir($dir)) {
			
			$objects = scandir($dir);  
			
			foreach ($objects as $object) {

				if ($object != "." && $object != "..") {

					if (filetype($dir."/".$object) == "dir") {

						self::rrmdir($dir."/".$object);

					} else {

						unlink($dir."/".$object);

					}
				}

			}

			reset($objects);

			rmdir($dir);
			
		}  
	}

}