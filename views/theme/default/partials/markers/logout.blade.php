<div{{$options}}>

	<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
		<i class="icon-user"></i>
		{{ Auth::user()->username }}
		<span class="caret"></span>
	</button>

	<ul class="dropdown-menu">
		<li>
			<a href="{{URL::to_action('site@logout')}}">{{ LL('cms::title.logout', SITE_LANG) }}</a>
		</li>
	</ul>

</div>