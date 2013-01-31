<?php

class Cms_Theme_Task {

	//THEME CMS TASK
	public function run($arguments = array())
	{

		if(!empty($arguments)) $new_theme = $arguments[0];

		if(!empty($new_theme)) {

			//CLONE DEFAULT THEME
			$theme_folder = path('bundle').'cms'.DS.'views'.DS.'theme'.DS;

			if(!file_exists($theme_folder . $new_theme)) {

				// COPY THEME
				File::cpdir($theme_folder . 'default', $theme_folder . $new_theme,false);

				echo PHP_EOL;
				echo 'Theme ['.$new_theme.'] created!'.PHP_EOL;

			} else {

				echo PHP_EOL;
				echo 'Theme ['.$new_theme.'] already exists!'.PHP_EOL;

			}

		} else {

			echo PHP_EOL;
			echo 'ERROR: no new theme name provided!'.PHP_EOL;

		}

	}

}