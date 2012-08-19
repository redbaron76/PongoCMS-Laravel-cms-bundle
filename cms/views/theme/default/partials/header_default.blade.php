<div class="row">

	<div class="span6">

		<a href="{{URL::base()}}" id="logo">
			<img src="{{MEDIA('img/pongocms_logo.png')}}" width="380" height="60">
			<span>{{MARKER('[$TRANSLATION[{"key":"a CMS built upon Laravel 3.2 PHP Framework"}]]')}}</span>
		</a>

	</div>

	<div class="span4">

		{{MARKER('[$SOCIAL[{"what":"facebook-twitter-follow","user":"PongoCMS"}]]')}}

	</div>

	<div class="span2">

		<div id="lang_block">

			<h6>{{MARKER('[$TRANSLATION[{"key":"site language"}]]')}}</h6>

			{{MARKER('[$LANG[{"id":"lang"}]]')}}

		</div>		

	</div>

</div>

<div class="row">

	{{MARKER('[$MENU[{"name":"nav"}]]')}}

</div>