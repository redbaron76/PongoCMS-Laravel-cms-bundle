<?php

class Cms_Backup_Task {

	public function run()
	{
		// RUN DEFAULT ACTION
		$result = shell_exec('php artisan cms::backup:theme');

		echo $result;
	}

	public function theme($arguments = array())
	{

		$current_theme = Config::get('cms::settings.theme');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		//COPY THEME ASSETS FROM /PUBLIC TO THEME PUBLIC
		$asset_path = path('bundle').'cms/views/theme/'.$theme.'/public';
		$public_path = path('public');

		$theme_path = path('bundle').'cms/views/theme/'.$theme;
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

		echo PHP_EOL;
		echo 'Backup for theme ['.$theme.'] completed!'.PHP_EOL;

	}

	public function restore($arguments = array())
	{

		$current_theme = Config::get('cms::settings.theme');

		if (array_key_exists(0, $arguments)) {

			$theme = $arguments[0];

		} else {

			$theme = $current_theme;

		}

		$backup_path = path('base').'_backup/'.$theme;

		if(file_exists($backup_path)) {

			$theme_path = path('bundle').'cms/views/theme/'.$theme;

			//COPY FROM _BACKUP TO THEME
			File::cpdir($backup_path, $theme_path, false);

			$result = '['.$theme.'] backup restored!'.PHP_EOL;

		} else {

			$result = '['.$theme.'] backup folder doesn\'t exists!'.PHP_EOL;

		}

		echo PHP_EOL;
		echo $result;

	}

	public function update()
	{

		$current_theme = Config::get('cms::settings.theme');

		//PERFORM BACKUP THEME
		$result = shell_exec('php artisan cms::backup:theme '.$current_theme);

		//PERFORM BUNDLE UPGRADE
		$result .= shell_exec('php artisan bundle:install pongocms');

		//PERFORM RESTORE THEME
		$result .= shell_exec('php artisan cms::backup:restore '.$current_theme);

		//PERFORM THEME SETUP
		$result .= shell_exec('php artisan cms::setup:theme '.$current_theme);

		echo PHP_EOL;
		echo $result;

	}
	
}

