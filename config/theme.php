<?php

/*
|--------------------------------------------------------------------------
| Return specific theme settings
|--------------------------------------------------------------------------|
*/

$THEME_NAME = 'default';

require path('bundle').'cms/views/theme/'.$THEME_NAME.'/theme'.EXT;

return $THEME_SETTINGS;