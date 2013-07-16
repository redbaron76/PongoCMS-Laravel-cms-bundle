<?php

/*
|--------------------------------------------------------------------------
| Return specific theme settings
|--------------------------------------------------------------------------|
*/

require path('bundle').'cms/views/theme/'.Config::get('cms::settings.theme').'/theme'.EXT;

return $THEME_SETTINGS;