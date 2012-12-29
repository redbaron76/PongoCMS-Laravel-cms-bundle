<div class="row">
	<div class="span4">
		<h2>{{LL('cms::title.tags', CMSLANG)}}</h2>
	</div>
	<div class="span6 toright">		
		@if (!empty($lang))
		<div class="input-prepend">
			<span class="add-on">{{LL('cms::form.page_display', CMSLANG)}}:</span>
			{{Form::select('banner_lang', Config::get('cms::settings.langs'), $lang, array('id' => 'change_lang', 'class' => 'span2'))}}
		</div>
		@else
		&nbsp;
		@endif
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_tag', CMSLANG)}}
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				@foreach (Config::get('cms::settings.langs') as $key => $value)
					<li>
						<a href="{{action('cms::tag@new', array($key))}}">
							<i class="icon-chevron-right"></i>
							{{$value}}
						</a>
					</li>
				@endforeach
			</ul>
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
					<th>{{LL('cms::label.tagname', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">
				@forelse ($data->results as $tag)
				<tr class="post">
					<td>{{$tag->name}}</td>
                    <td>

                    	<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::tag@edit', array($tag->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							<div class="btn-group">
								<a href="#modal-delete-{{$tag->id}}" class="btn btn-mini btn-danger" data-toggle="modal">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
						</div>

						<div class="modal hide" id="modal-delete-{{$tag->id}}">
							{{Form::open(action('cms::tag@delete'), 'POST')}}
							{{Form::hidden('tag_id', $tag->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_tag', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{LABEL('cms::tag.', $tag->name)}}</p>
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

				@if($data->total > Config::get('cms::theme.pag') and $data->page < $data->last)
				<tr class="navigation">
					<td colspan="4">{{$data->next()}}</td>
				</tr>
				@endif
				
			</tbody>
		</table>
		
	</div>
</div>
