<!doctype html>
<html>
	<head>		
		<title>{{$title}}</title>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="{{Config::get('application.url')}}/favicon.ico" />
		{{Asset::container('header')->styles()}}
    	{{Asset::container('header')->scripts()}}
	</head>
	<body>
