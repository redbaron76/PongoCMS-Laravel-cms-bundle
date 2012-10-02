<!doctype html>
<html>
	<head>		
		<title>{{$title}} | {{Config::get('cms::theme.project_name')}}</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="{{SITE_LANG}}" />
		<meta name="description" content="{{$descr}}" />
		<meta name="keywords" content="{{$keyw}}" />
		<meta name="generator" content="{{Config::get('cms::settings.generator')}}" />
		<meta name="robots" content="index,follow" />
		<meta name="google-site-verification" content="{{Config::get('cms::settings.verification')}}" />
		<link rel="shortcut icon" href="favicon.ico" />
		{{Asset::container('header')->styles()}}
		{{Asset::container('header')->scripts()}}
		<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script><![endif]-->
	</head>
	<body>

		<div id="wrapper" class="container">

			<div id="header">
				{{$header}}
			</div>

			<div id="layout">
				{{$layout}}
			</div>							

		</div>

		<footer class="container">
			{{$footer}}
		</footer>

		{{Asset::container('footer')->scripts()}}

	</body>
</html>
