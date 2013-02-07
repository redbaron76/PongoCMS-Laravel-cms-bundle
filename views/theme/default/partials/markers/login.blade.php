{{Form::open(URL::to_action('site@login'), 'POST', $options)}}
	
	{{Form::hidden('back_url', Session::get('back_url', SLUG_FULL))}}

	@if(Session::has('login_error_msg'))
	<div class="control-group">
		<p class="text-error">
			{{Session::get('login_error_msg')}}
		</p>
	</div>
	@endif

	<div class="control-group">
		{{Form::label('username', LL('cms::form.user_username', SITE_LANG))}}
		{{Form::text('username', Input::old('username'))}}
	</div>

	<div class="control-group">
		{{Form::label('password', LL('cms::form.password', SITE_LANG))}}
		{{Form::password('password')}}
	</div>
	
	<label class="checkbox">
	{{Form::checkbox('remember', 1, true);}}
	{{HTML::span(LL('cms::form.user_remember', SITE_LANG))}}
	</label>

	{{Form::submit(LL('cms::button.enter', SITE_LANG), array('class' => 'btn'))}}

{{Form::close()}}
