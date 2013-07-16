<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::role')}}" class="btn btn-inverse pull-right">
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
					<li class="active"><a href="#role" data-toggle="tab">{{LL('cms::form.role', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- ROLE FORM TAB -->
					<div class="tab-pane active" id="role">
						{{Form::open(action('cms::ajax_role@save_role'), 'POST', array('class' => 'form-vertical', 'id' => 'form_role')) . "\n"}}
						{{Form::hidden('role_id', $role_id, array('class' => 'role_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.role_legend', CMSLANG)}}</legend>

								<div class="control-group" rel="role_name">
									{{Form::label('role_name', LL('cms::form.role_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('role_name', $role_name, array('class' => 'span4', 'id' => 'role_name')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="role_level">
									{{Form::label('role_level', LL('cms::form.role_level', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('role_level', $role_level, $role_level_selected, array('class' => 'span3', 'id' => 'role_level')) . "\n"}}
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_role">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::role')}}" class="btn btn-danger save_form" rel="form_role">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::role')}}" class="btn">
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