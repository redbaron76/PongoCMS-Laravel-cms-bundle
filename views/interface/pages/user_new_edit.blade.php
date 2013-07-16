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
					<li{{DISABLED($user_id)}}><a href="#details" data-toggle="tab">{{LL('cms::form.details', CMSLANG)}}</a></li>
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
								<div class="control-group" rel="user_editor">
									{{Form::label('user_editor', LL('cms::form.user_editor', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('user_editor', $user_editor, $user_editor_selected, array('class' => 'span3', 'id' => 'user_editor')) . "\n"}}
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

					<!-- DETAILS FORM TAB -->
					<div class="tab-pane" id="details">
						{{Form::open(action('cms::ajax_user@save_details'), 'POST', array('class' => 'form-vertical', 'id' => 'form_details')) . "\n"}}
						{{Form::hidden('user_id', $user_id, array('class' => 'user_id')) . "\n"}}
						{{Form::hidden('detail_id', $detail_id, array('class' => 'detail_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.user_details_legend', CMSLANG)}}</legend>

								<div class="control-group" rel="user_name">
									{{Form::label('user_name', LL('cms::form.user_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_name', $user_name, array('class' => 'span4', 'id' => 'user_name')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_surname">
									{{Form::label('user_surname', LL('cms::form.user_surname', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_surname', $user_surname, array('class' => 'span4', 'id' => 'user_surname')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_address">
									{{Form::label('user_address', LL('cms::form.user_address', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_address', $user_address, array('class' => 'span6', 'id' => 'user_address')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_number">
									{{Form::label('user_number', LL('cms::form.user_number', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_number', $user_number, array('class' => 'span2', 'id' => 'user_number')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_city">
									{{Form::label('user_city', LL('cms::form.user_city', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_city', $user_city, array('class' => 'span6', 'id' => 'user_city')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_zip">
									{{Form::label('user_zip', LL('cms::form.user_zip', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_zip', $user_zip, array('class' => 'span2', 'id' => 'user_zip')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_state">
									{{Form::label('user_state', LL('cms::form.user_state', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_state', $user_state, array('class' => 'span2', 'id' => 'user_state')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_country">
									{{Form::label('user_country', LL('cms::form.user_country', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_country', $user_country, array('class' => 'span4', 'id' => 'user_country')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_tel">
									{{Form::label('user_tel', LL('cms::form.user_tel', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_tel', $user_tel, array('class' => 'span4', 'id' => 'user_tel')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="user_cel">
									{{Form::label('user_cel', LL('cms::form.user_cel', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('user_cel', $user_cel, array('class' => 'span4', 'id' => 'user_cel')) . "\n"}}
									</div>
								</div>

								<div class="control-group" rel="user_info">
									{{Form::label('user_info', LL('cms::form.user_info', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::textarea('user_info', $user_info, array('class' => 'span6', 'rows' => '5', 'id' => 'user_cel')) . "\n"}}
									</div>
								</div>

								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_details">
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
