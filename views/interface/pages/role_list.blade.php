<div class="row">
	<div class="span10">
		<h2>{{LL('cms::title.roles', CMSLANG)}}</h2>
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" href="{{action('cms::role@new')}}">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_role', CMSLANG)}}
			</a>
		</div>
	</div>
</div>

<div class="row space">
	<div class="span12">
		<table class="table table-striped fixed v-middle">
			<col width="70%">
			<col width="15%">
			<col width="15%">
			<thead>
				<tr>
					<th>{{LL('cms::label.rolename', CMSLANG)}}</th>
					<th>{{LL('cms::label.level', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">
				@forelse ($data as $role)
				<tr class="post">
					<td>{{ucfirst(LABEL('cms::role.', $role->name))}}</td>
                    <td>{{$role->level}}</td>
                    <td>
                    	@if(!array_key_exists($role->name, Config::get('cms::settings.roles')))

                    	<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::role@edit', array($role->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							<div class="btn-group">
								<a href="#modal-delete-{{$role->id}}" class="btn btn-mini btn-danger" data-toggle="modal">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
						</div>

						<div class="modal hide" id="modal-delete-{{$role->id}}">
							{{Form::open(action('cms::role@delete'), 'POST')}}
							{{Form::hidden('role_id', $role->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_role', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{LABEL('cms::role.', $role->name)}}</p>
							</div>
							<div class="modal-footer">
								<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
								{{Form::submit(LL('cms::button.delete', CMSLANG), array('class' => 'btn btn-danger'))}}
							</div>
							{{Form::close()}}
						</div>

						@else
							<span class="label">SYSTEM</span>
                    	@endif
                    </td>
				</tr>
				@empty
				<tr>
					<td colspan="3" class="toleft">{{LL('cms::alert.list_empty', CMSLANG)}}</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>