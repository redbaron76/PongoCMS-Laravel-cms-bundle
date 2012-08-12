var BASE = '{{Config::get('application.url')}}';
var MAX_UP = '{{strtolower(str_replace(' ','',MEDIA_SIZE(Config::get('cms::settings.max_size'),'KB')))}}';