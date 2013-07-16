@if(Auth::check() and (ROLE >= Config::get('cms::settings.roles.editor')))
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="{{URL::base() . Config::get('cms::settings.url')}}">{{Config::get('cms::settings.name')}}</a>
			{{$topbar}}
		</div>
	</div>
</div>
@endif

<?php $compact = (Auth::check() and (ROLE >= Config::get('cms::settings.roles.editor'))) ? '' : ' compact' ?>

<div class="container{{$compact}}">	
	<div class="content">