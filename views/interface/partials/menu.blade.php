<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="{{URL::base() . Config::get('cms::settings.url')}}">{{Config::get('cms::settings.name')}}</a>
			{{$topbar}}
		</div>
	</div>
</div>

<div class="container">	
	<div class="content">