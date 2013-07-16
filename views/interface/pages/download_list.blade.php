<div class="row">
	<div class="span10">
		<h2>{{LL('cms::title.downloads', CMSLANG)}}</h2>
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" href="{{action('cms::download@new')}}">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_download', CMSLANG)}}
			</a>
		</div>
	</div>
</div>

<div class="row space">
	<div class="span12">
		<table class="table table-striped fixed v-middle">
			<col width="85%">
			<col width="15%">
			<thead>
				<tr>
					<th>{{LL('cms::label.downloadname', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">
				@forelse ($data as $download)
				<tr>
					<td>{{$download->name}}</td>
                    <td>

                    	<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::download@edit', array($download->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							<div class="btn-group">
								<a href="#modal-delete-{{$download->id}}" class="btn btn-mini btn-danger" data-toggle="modal">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
						</div>

						<div class="modal hide" id="modal-delete-{{$download->id}}">
							{{Form::open(action('cms::download@delete'), 'POST')}}
							{{Form::hidden('download_id', $download->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_download', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{$download->name}}</p>
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
					<td colspan="3" class="toleft">{{LL('cms::alert.list_empty', CMSLANG)}}</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>