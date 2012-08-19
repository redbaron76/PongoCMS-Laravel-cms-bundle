{{Form::open(action('cms::login'), 'POST')}}
<fieldset>
	<legend>{{LL('cms::form.legend_login', CMSLANG)}}</legend>
	<div class="control-group">
		<div class="controls">
			<input class="input-medium" type="text" value="{{Input::old('username')}}" placeholder="{{LL('cms::form.username', CMSLANG)}}" name="username">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input class="input-medium" type="password" placeholder="{{LL('cms::form.password', CMSLANG)}}" name="password">
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input class="btn" type="submit" value="{{LL('cms::button.enter', CMSLANG)}}">
		</div>
	</div>
</fieldset>
{{Form::close()}}