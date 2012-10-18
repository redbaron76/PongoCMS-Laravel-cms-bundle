<div class="row">
	<div class="span10">
		<h2>{{LL('cms::title.users', CMSLANG)}}</h2>
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" href="{{action('cms::user@new')}}">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_user', CMSLANG)}}
			</a>
		</div>
	</div>
</div>

<div class="row space">
	<div class="span12">
		<table class="table table-striped fixed v-middle">
			<col width="25%">
			<col width="40%">
			<col width="20%">
			<col width="15%">
			<thead>
				<tr>
					<th>{{LL('cms::label.username', CMSLANG)}}</th>
					<th>{{LL('cms::label.email', CMSLANG)}}</th>
					<th>{{LL('cms::label.rolename', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">
				@forelse ($data->results as $user)
				<tr>
					<td>{{$user->username}}</td>
					<td>{{$user->email}}</td>
                    <td>{{LABEL('cms::role.', $user->role->name)}} ({{$user->role->level}})</td>
                    <td>                    	

                    	<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::user@edit', array($user->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							@if(!array_key_exists($user->username, Config::get('cms::settings.roles')) and $user->username != USERNAME)
							<div class="btn-group">
								<a href="#modal-delete-{{$user->id}}" class="btn btn-mini btn-danger" data-toggle="modal">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
                    		@endif
						</div>

						<div class="modal hide" id="modal-delete-{{$user->id}}">
							{{Form::open(action('cms::user@delete'), 'POST')}}
							{{Form::hidden('user_id', $user->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_user', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{$user->username}}</p>
							</div>
							<div class="modal-footer">
								<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
								{{Form::submit(LL('cms::button.delete', CMSLANG), array('class' => 'btn btn-danger'))}}
							</div>
							{{Form::close()}}
						</div>
						
                    </td>
				</tr>
				@empty
				<tr>
					<td colspan="3">{{LL('cms::alert.list_empty', CMSLANG)}}</td>
				</tr>
				@endforelse

				@if($data->total > Config::get('cms::theme.pag') and $data->page < $data->last)
				<tr class="navigation">
					<td colspan="4">{{$data->next()}}</td>
				</tr>
				@endif

			</tbody>
		</table>
	</div>
</div>
