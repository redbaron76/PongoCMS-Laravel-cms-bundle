<div class="row">
	<div class="span10">
		<h2>{{LL('cms::title.galleries', CMSLANG)}}</h2>
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" href="{{action('cms::gallery@new')}}">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_gallery', CMSLANG)}}
			</a>
		</div>
	</div>
</div>

<div class="row space">
	<div class="span12">
		<table class="table table-striped fixed v-middle">
			<col width="60%">
			<col width="25%">
			<col width="15%">
			<thead>
				<tr>
					<th>{{LL('cms::label.galleryname', CMSLANG)}}</th>
					<th>{{LL('cms::label.thumblabel', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">
				@forelse ($data as $gallery)
				<tr>
					<td>{{$gallery->name}}</td>
					<td>{{$gallery->thumb}}</td>
                    <td>

                    	<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::gallery@edit', array($gallery->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							<div class="btn-group">
								<a href="#modal-delete-{{$gallery->id}}" class="btn btn-mini btn-danger" data-toggle="modal">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
						</div>

						<div class="modal hide" id="modal-delete-{{$gallery->id}}">
							{{Form::open(action('cms::gallery@delete'), 'POST')}}
							{{Form::hidden('gallery_id', $gallery->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_gallery', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{$gallery->name}}</p>
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