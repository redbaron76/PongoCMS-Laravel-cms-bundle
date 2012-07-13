{{Form::open(URL::to_action('site@login'), 'POST', $options)}}
	
	{{Form::label('username', LL('user.user_username', SITE_LANG))}}
	{{Form::text('username', Input::old('username'))}}

	{{Form::label('password', LL('user.user_password', SITE_LANG))}}
	{{Form::password('password')}}
	
	{{Form::checkbox('remember', 1, true);}}
	{{HTML::span(LL('user.user_remember', SITE_LANG))}}
	
	{{Form::submit(LL('user.user_login_button', SITE_LANG))}}

{{Form::close()}}