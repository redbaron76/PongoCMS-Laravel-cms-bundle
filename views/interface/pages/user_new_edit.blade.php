<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::user')}}" class="btn btn-inverse pull-right">
			<i class="icon-arrow-left icon-white"></i>
			{{LL('cms::button.back', CMSLANG)}}
		</a>
	</div>
</div>

<div class="row space">
	<div class="span12">

		<div class="row">
			<div class="span2 side tabbable tabs-left">
				
				<ul class="nav nav-tabs">
					<li class="active"><a href="#account" data-toggle="tab">{{LL('cms::form.account', CMSLANG)}}</a></li>
					<li{{DISABLED($user_id)}}><a href="#password" data-toggle="tab">{{LL('cms::form.password', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- ACCOUNT FORM TAB -->
					<div class="tab-pane active" id="account">
						{{Form::open(action('cms::ajax_user@save_account'), 'POST', array('class' => 'form-vertical', 'id' => 'form_account')) . "\n"}}
						{{Form::hidden('user_id', $user_id, array('class' => 'user_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.user_account_legend', CMSLANG)}}</legend>

								<div class="control-group" rel="user_username">
									{{Form::label('user_username', LL('cms::form.user_username', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_username', $user_username, array('class' => 'span4', 'id' => 'user_username')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_email">
									{{Form::label('user_email', LL('cms::form.user_email', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_email', $user_email, array('class' => 'span4', 'id' => 'user_email')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_role">
									{{Form::label('user_role', LL('cms::form.user_role', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('user_role', $user_role, $user_role_selected, array('class' => 'span3', 'id' => 'user_role')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_lang">
									{{Form::label('user_lang', LL('cms::form.user_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('user_lang', $user_lang, $user_lang_selected, array('class' => 'span3', 'id' => 'user_lang')) . "\n"}}
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<label class="checkbox">
											{{Form::checkbox('is_valid', 1, $user_is_valid, array('id' => 'user_is_valid'))}}
											{{LL('cms::form.user_is_valid', CMSLANG)}}
										</label>
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_account">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::user')}}" class="btn btn-danger save_form" rel="form_account">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::user')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>

							</fieldset>
						{{Form::close()}}
					</div>

					<!-- PASSWORD FORM TAB -->
					<div class="tab-pane" id="password">
						{{Form::open(action('cms::ajax_user@save_password'), 'POST', array('class' => 'form-vertical', 'id' => 'form_password')) . "\n"}}
						{{Form::hidden('user_id', $user_id, array('class' => 'user_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.user_password_legend', CMSLANG)}}</legend>

								<div class="control-group" rel="user_password">
									{{Form::label('user_password', LL('cms::form.user_password', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::password('user_password', array('class' => 'span4', 'id' => 'user_password')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_password_confirmation">
									{{Form::label('user_password_confirmation', LL('cms::form.user_password_confirmation', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::password('user_password_confirmation', array('class' => 'span4', 'id' => 'user_password_confirmation')) . "\n"}}
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_password">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::user')}}" class="btn btn-danger save_form" rel="form_account">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::user')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>

							</fieldset>
						{{Form::close()}}
					</div>

				</div>

			</div>
		</div>

	</div>
</div>