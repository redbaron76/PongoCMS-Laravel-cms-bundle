<?php

class Cms_Backup_Task {

	/**
	 * 	Run backup task of then current theme
	 *
	 * 	@return string
	 */
	public function run()
	{
		// RUN DEFAULT ACTION
		$result = shell_exec('php artisan cms::backup:theme');

		echo $result;
	}

	/**
	 *	Run backup task of any theme
	 *	Current theme if not specified
	 *
	 * 	@param  array
	 * 	@return string
	 */
	public function theme($arguments = array())
	{

		// SYNC PUBLIC TO THEME
		shell_exec('php artisan cms::backup:sync');

		$current_theme = Config::get('cms::settings.theme');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		//COPY THEME ASSETS FROM /PUBLIC TO THEME PUBLIC
		$asset_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme.DS.'public';
		$public_path = path('public');

		$theme_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme;
		$backup_path = path('base').'_backup';

		//ITEMS TO COPY
		$items = array('css','files','img','js','samples');

		//ITERATE COPY
		foreach ($items as $item) {

			if(is_dir($public_path.$item)) {

				//COPY TO BUNDLE
				File::cpdir($public_path.$item, $asset_path.DS.$item, false);

			}

		}

		//COPY TO _BACKUP
		File::cpdir($theme_path, $backup_path.DS.$theme, false);

		// COPY CURRENT SETTINGS
		$settings_path = path('bundle').'cms'.DS.'config';
		File::cpdir($settings_path, $backup_path.DS.$theme.DS.'_config', false);

		// COPY CURRENT APP CONTROLLERS
		$controllers_path = path('app').DS.'controllers';
		File::cpdir($controllers_path, $backup_path.DS.$theme.DS.'_controllers', false);

		echo PHP_EOL;
		echo 'Backup for theme ['.$theme.'] completed!'.PHP_EOL;

	}

	/**
	 *	Run sync task on bundle assets
	 *	Good in development before Git push!
	 *
	 * 	@param  array
	 * 	@return string
	 */
	public function sync($arguments = array())
	{

		//ITEMS TO COPY
		$items = array('css','img','js');

		// COPY ASSET FROM PUBLIC/BUNDLES TO BUNDLES/PUBLIC

		$from_path 	= path('public').'bundles'.DS.'cms';
		$to_path 	= path('bundle').'cms'.DS.'public';

		//ITERATE COPY
		foreach ($items as $item) {

			if(is_dir($from_path.DS.$item)) {

				//COPY TO BUNDLES/PUBLIC
				File::cpdir($from_path.DS.$item, $to_path.DS.$item, false);

			}

		}

		// COPY ASSET FROM PUBLIC TO CURRENT_THEME/PUBLIC

		$current_theme = Config::get('cms::settings.theme');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		//COPY THEME ASSETS FROM /PUBLIC TO THEME PUBLIC

		$asset_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme.DS.'public';
		$public_path = path('public');

		//ITERATE COPY
		foreach ($items as $item) {

			if(is_dir($public_path.DS.$item)) {

				//COPY TO BUNDLES/PUBLIC
				File::cpdir($public_path.DS.$item, $asset_path.DS.$item, false);

			}

		}

		echo PHP_EOL;
		echo 'Asset sync completed!'.PHP_EOL;

	}

	/**
	 *	It restores a backed up theme
	 *	Current theme if not specified
	 *
	 * 	@param  array
	 * 	@return string
	 */
	public function restore($arguments = array())
	{

		$current_theme = Config::get('cms::settings.theme');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		$backup_path = path('base').'_backup'.DS.$theme;

		if(file_exists($backup_path)) {

			$theme_path = path('bundle').'cms'.DS.'views'.DS.'theme'.DS.$theme;

			//COPY FROM _BACKUP TO THEME
			File::cpdir($backup_path, $theme_path, false);

			$result = '['.$theme.'] backup restored!'.PHP_EOL;

		} else {

			$result = '['.$theme.'] backup folder doesn\'t exists!'.PHP_EOL;

		}

		echo PHP_EOL;
		echo $result;

	}

	/**
	 *	Run update task on PongoCMS
	 *
	 * 	@param  array
	 * 	@return string
	 */
	public function update()
	{

		$current_theme = Config::get('cms::settings.theme');

		//PERFORM BACKUP THEME
		$result = shell_exec('php artisan cms::backup:theme '.$current_theme);

		//PERFORM BUNDLE UPGRADE
		$result .= shell_exec('php artisan bundle:install pongocms');

		//COPY BUNDLE ASSET
		$result .= shell_exec('php artisan bundle:publish cms');

		//PERFORM RESTORE THEME
		$result .= shell_exec('php artisan cms::backup:restore '.$current_theme);

		//PERFORM THEME SETUP
		$result .= shell_exec('php artisan cms::setup:theme '.$current_theme);

		echo PHP_EOL;
		echo $result;

	}
	
}

