@if (array_key_exists('dashboard', $sections) and ROLE >= $sections['dashboard']['level'])
<ul class="nav">
	<li class="{{ (URI::is('cms/dashboard') or URI::is('cms')) ? 'active' : '' }}">
		<a href="{{URL::to_action('cms::'.$sections['dashboard']['path'])}}">{{LL('cms::menu.dashboard', CMSLANG)}}</a>
	</li>
</ul>
@endif

@if (array_key_exists('pages', $sections) and (ROLE >= $sections['pages']['level']))

	<?php
		$is_active = false;
		if(URI::is('cms/page*')) $is_active = true;
	?>

<ul class="nav">
	<li class="{{ (URI::is($sections['pages']['path'].'*')) ? 'active' : '' }}">
		<a href="{{URL::to_action('cms::'.$sections['pages']['path'])}}">{{LL('cms::menu.pages', CMSLANG)}}</a>
	</li>
</ul>
@endif

@if (array_key_exists('contents', $sections))

	<?php
		$is_active = false;
		if(URI::is('cms/blog*')) $is_active = true;
		if(URI::is('cms/calendar*')) $is_active = true;
	?>

	@foreach ($sections['contents'] as $key => $value)
		<?php if(URI::is('cms/'.$value['path'].'*')) $is_active = true; ?>
	@endforeach

<ul class="nav">
	<li class="dropdown{{($is_active) ? ' active' : ''}}" data-dorpdown="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			{{LL('cms::menu.contents', CMSLANG)}}
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
		@foreach ($sections['contents'] as $key => $value)
			@if (ROLE >= $value['level'])
			<li class="{{ (URI::is($value['path'].'*')) ? 'active' : '' }}">
				<a href="{{URL::to_action('cms::'.$value['path'])}}">{{LL('cms::menu.'.$key, CMSLANG)}}</a>
			</li>
			@endif
		@endforeach
		</ul>
	</li>
</ul>
@endif

@if (array_key_exists('services', $sections))

	<?php
		$is_active = false;
		if(URI::is('cms/menu*')) $is_active = true;
		if(URI::is('cms/file*')) $is_active = true;
		if(URI::is('cms/download*')) $is_active = true;
		if(URI::is('cms/gallery*')) $is_active = true;
		if(URI::is('cms/translation*')) $is_active = true;
		if(URI::is('cms/tag*')) $is_active = true;
		if(URI::is('cms/banner*')) $is_active = true;
	?>

<ul class="nav">
	<li class="dropdown{{($is_active) ? ' active' : ''}}" data-dorpdown="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			{{LL('cms::menu.services', CMSLANG)}}
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
		@foreach ($sections['services'] as $key => $value)
			@if (ROLE >= $value['level'])
			<li class="{{ (URI::is($value['path'].'*')) ? 'active' : '' }}">
				<a href="{{URL::to_action('cms::'.$value['path'])}}">{{LL('cms::menu.'.$key, CMSLANG)}}</a>
			</li>
			@endif
		@endforeach
		</ul>
	</li>
</ul>
@endif

@if (array_key_exists('access', $sections) and (ROLE >= $sections['access']['roles']['level'] or ROLE >= $sections['access']['users']['level']))

	<?php
		$is_active = false;
		if(URI::is('cms/role*')) $is_active = true;
		if(URI::is('cms/user*')) $is_active = true;
	?>

<ul class="nav">
	<li class="dropdown{{($is_active) ? ' active' : ''}}" data-dorpdown="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			{{LL('cms::menu.access', CMSLANG)}}
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
		@foreach ($sections['access'] as $key => $value)
			@if (ROLE >= $value['level'])
			<li class="{{ (URI::is($value['path'].'*')) ? 'active' : '' }}">
				<a href="{{URL::to_action('cms::'.$value['path'])}}">{{LL('cms::menu.'.$key, CMSLANG)}}</a>
			</li>
			@endif
		@endforeach
		</ul>
	</li>
</ul>
@endif

<?php
	$roles = Config::get('cms::settings.roles');
	$role = array_search(ROLE, $roles);
?>

<ul class="nav pull-right">
	<li><span class="label">{{LL('cms::role.'.$role, CMSLANG)}}</span></li>
	<li class="dropdown" data-dorpdown="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			{{USERNAME}}
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">

			<li class="nav-header">{{LL('cms::label.editor', CMSLANG)}}</li>

			@foreach (Config::get('cms::settings.editor') as $editor => $name)

			<li{{(EDITOR == $editor) ? ' class="active"' : ''}}>
				<a href="{{URL::to_action('cms::dashboard@editor', array($editor))}}">{{$name}}</a>
			</li>
			@endforeach

			<li class="divider"></li>

			<li class="nav-header">{{LL('cms::label.language', CMSLANG)}}</li>

			@foreach ($interface as $code => $lang)

			<li{{(CMSLANG == $code) ? ' class="active"' : ''}}>
				<a href="{{URL::to_action('cms::dashboard@lang', array($code))}}">{{$lang}}</a>
			</li>
			@endforeach

			<li class="divider"></li>

			<li><a href="{{URL::to_action('cms::login@logout')}}">{{LL('cms::title.logout', CMSLANG)}}</a></li>

		</ul>		
	</li>
</ul>
