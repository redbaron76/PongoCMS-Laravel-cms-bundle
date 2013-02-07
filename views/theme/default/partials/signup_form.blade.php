<?php

	$signup_name = (strlen(Session::get('signup_name_error')) > 0) ? ' error': null;
	$signup_surname = (strlen(Session::get('signup_surname_error')) > 0) ? ' error': null;
	$signup_address = (strlen(Session::get('signup_address_error')) > 0) ? ' error': null;
	//INFO
	$signup_number = (strlen(Session::get('signup_number_error')) > 0) ? ' error': null;
	$signup_city = (strlen(Session::get('signup_city_error')) > 0) ? ' error': null;
	$signup_zip = (strlen(Session::get('signup_zip_error')) > 0) ? ' error': null;
	$signup_state = (strlen(Session::get('signup_state_error')) > 0) ? ' error': null;
	$signup_country = (strlen(Session::get('signup_country_error')) > 0) ? ' error': null;
	$signup_cel = (strlen(Session::get('signup_cel_error')) > 0) ? ' error': null;

	$signup_email = (strlen(Session::get('signup_email_error')) > 0) ? ' error': null;
	$signup_password = (strlen(Session::get('signup_password_error')) > 0) ? ' error': null;

?>

	<h1>{{LL('cms::form.user_signup_title', LANG)}}</h1>

	{{Form::open(URL::to_action('site@signup'), 'POST')}}

		<div class="control-group{{$signup_name}}">
			<label for="signup-name">{{LL('cms::form.user_name', LANG)}} *</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_name')}}" id="signup-name" class="span5" name="signup_name" placeholder="{{LL('cms::form.user_name', LANG)}}">
				@if(isset($signup_name))
				<span class="help-block">{{Session::get('signup_name_error')}}</span>
				@endif
			</div>
		</div>

		<div class="control-group{{$signup_surname}}">
			<label for="signup-surname">{{LL('cms::form.user_surname', LANG)}} *</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_surname')}}" id="signup-surname" class="span5" name="signup_surname" placeholder="{{LL('cms::form.user_surname', LANG)}}">
				@if(isset($signup_surname))
				<span class="help-block">{{Session::get('signup_surname_error')}}</span>
				@endif
			</div>
		</div>

		<div class="control-group{{$signup_address}}{{$signup_number}}">
			<label for="signup-address">{{LL('cms::form.user_address', LANG)}} {{LL('cms::form.user_number', LANG)}} *</label>
			<div class="controls controls-row">
				<input type="text" value="{{Input::old('signup_address')}}" id="signup-address" class="span4" name="signup_address" placeholder="{{LL('cms::form.user_address', LANG)}}">
				<input type="text" value="{{Input::old('signup_number')}}" id="signup-number" class="span1" name="signup_number" placeholder="">
			</div>
			@if(isset($signup_address) or isset($signup_number))
			<span class="help-block">{{Session::get('signup_address_error')}} {{Session::get('signup_number_error')}}</span>
			@endif				
		</div>

		<div class="control-group{{$signup_city}}{{$signup_state}}">
			<label for="signup-city">{{LL('cms::form.user_city', LANG)}} {{LL('cms::form.user_state', LANG)}} *</label>
			<div class="controls controls-row">
				<input type="text" value="{{Input::old('signup_city')}}" id="signup-city" class="span4" name="signup_city" placeholder="{{LL('cms::form.user_city', LANG)}}">
				<input type="text" value="{{Input::old('signup_state')}}" id="signup-state" class="span1" name="signup_state" placeholder="">
			</div>
			@if(isset($signup_city) or isset($signup_state))
			<span class="help-block">{{Session::get('signup_city_error')}} {{Session::get('signup_state_error')}}</span>
			@endif				
		</div>

		<div class="control-group{{$signup_zip}}">
			<label for="signup-zip">{{LL('cms::form.user_zip', LANG)}} *</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_zip')}}" id="signup-zip" class="span2" name="signup_zip" placeholder="{{LL('cms::form.user_zip', LANG)}}">
				@if(isset($signup_zip))
				<span class="help-block">{{Session::get('signup_zip_error')}}</span>
				@endif
			</div>
		</div>

		<div class="control-group{{$signup_country}}">
			<label for="signup-country">{{LL('cms::form.user_country', LANG)}} *</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_country')}}" id="signup-country" class="span5" name="signup_country" placeholder="{{LL('cms::form.user_country', LANG)}}">
				@if(isset($signup_country))
				<span class="help-block">{{Session::get('signup_country_error')}}</span>
				@endif
			</div>
		</div>

		<h2>{{LL('cms::form.user_signup_contact_title', LANG)}}</h2>

		<div class="control-group">
			<label for="signup-tel">{{LL('cms::form.user_tel', LANG)}}</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_tel')}}" id="signup-tel" class="span5" name="signup_tel" placeholder="{{LL('cms::form.user_tel', LANG)}}">
			</div>
		</div>

		<div class="control-group{{$signup_cel}}">
			<label for="signup-cel">{{LL('cms::form.user_cel', LANG)}} *</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_cel')}}" id="signup-cel" class="span5" name="signup_cel" placeholder="{{LL('cms::form.user_cel', LANG)}}">
				@if(isset($signup_cel))
				<span class="help-block">{{Session::get('signup_cel_error')}}</span>
				@endif
			</div>
		</div>

		<h2>{{LL('cms::form.user_signup_contact_account', LANG)}}</h2>

		<div class="control-group{{$signup_email}}">
			<label for="signup-email">{{LL('cms::form.user_email', LANG)}} *</label>
			<div class="controls">
				<input type="text" value="{{Input::old('signup_email')}}" id="signup-email" class="span4" name="signup_email" placeholder="{{LL('cms::form.user_email', LANG)}}">
				@if(isset($signup_email))
				<span class="help-block">{{Session::get('signup_email_error')}}</span>
				@endif
			</div>
		</div>

		<div class="control-group{{$signup_password}}">
			<label for="signup-password">{{LL('cms::form.user_password', LANG)}} * <small>(6-8 caratteri)</small></label>
			<div class="controls">
				<input type="password" id="signup-password" class="span3" name="password">
				@if(isset($signup_password))
				<span class="help-block">{{Session::get('signup_password_error')}}</span>
				@endif
			</div>
		</div>

		<div class="control-group">
			<label for="password_confirmation">{{LL('cms::form.user_password_confirmation', LANG)}}</label>
			<div class="controls">
				<input type="password" id="password_confirmation" class="span3" name="password_confirmation">
			</div>
		</div>

		<h5>* {{LL('cms::form.user_signup_mandatory', LANG)}}</h5>

		{{Form::button(LL('cms::button.signup', SITE_LANG), array('class' => 'btn btn-primary'))}}

	{{Form::close()}}