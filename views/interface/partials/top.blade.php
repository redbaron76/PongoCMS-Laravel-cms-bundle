<div class="row page-header top-page">
	<div class="span7">

		<?php $name = (Auth::check()) ? Config::get('cms::theme.project_name') : Config::get('cms::settings.name') ?>

		<h1>{{$name}} <small>| {{LL('cms::title.slogan', CMSLANG)}}</small></h1>
	</div>
	@if ($search)
		<div class="span5">
			{{Form::open($search, 'POST', array('id' => 'search-form'))}}
			{{Form::text('q', $q, array('class' => 'span5', 'id' => 'search-input', 'placeholder' => LL('cms::form.searchcontent', CMSLANG)))}}
			{{Form::close()}}
		</div>
	@endif
</div>