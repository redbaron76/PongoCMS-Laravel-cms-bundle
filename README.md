# [PongoCMS](http://pongocms.com) - A CMS bundle for Laravel PHP Framework 3.2+

PongoCMS is a php CMS based on Laravel 3.2 and Twitter Bootstrap 2.0.4 that aims to build
multilingual and SEO optimized websites or blogs with ease and flexibility.

[Official Website & Documentation](http://pongocms.com)

## Feature Overview

- Theme based frontend development
- Laravel's Artisan CLI commands for **cms** and **theme** setup
- Full route management trough friendly urls
- Pages and Blog posts management
- Multiple file upload at once and auto thumbs creator
- Services for translations, image galleries, banner rotators
- Inline **Markers** for embed video, Google Maps, social toolbars, etc.
- ...a lot of [other things](http://pongocms.com/features)


## Download and config PongoCMS bundle

1. Download **latest release** of PongoCMS from GitHub .

2. Create a `cms` folder inside Laravel `/bundles` folder.

3. Copy downloaded content into `/bundles/cms` folder.

4. Config **cms** bundle in `/application/bundles.php` like this:


```php
return array(

    'cms' => array(
        'autoloads' => array(
            'map' => array(

                //CMS CORE
                'Cms_Base_Controller'       => '(:bundle)/controllers/cms_base.php',
                'Cms_Searchbase_Controller' => '(:bundle)/controllers/cms_searchbase.php',
                'Cms_Auth'                  => '(:bundle)/auth/cms_auth.php',

                //CMS METHODS
                'CmsRender'                 => '(:bundle)/libraries/render.php',
                'CmsUtility'                => '(:bundle)/libraries/utilities.php',

                //PLUG-INS
                'CleanOutput'               => '(:bundle)/libraries/cleanoutput.php',
                'gapi'                      => '(:bundle)/libraries/gapi.php',
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

);
```

## Setup and run PongoCMS demo site

1. Open a terminal console and move on **project root**.

2. Run Laravel Artisan CLI task command: `php artisan cms::setup`

3. Point your browser to your **base url**


## License

PongoCMS is open-sourced software licensed under the MIT License.