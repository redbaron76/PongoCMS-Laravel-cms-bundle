<div class="row">
	<div class="span4">
		<h2>{{LL('cms::title.blogs', CMSLANG)}}</h2>
	</div>
	<div class="span6 toright">		
		@if (!empty($lang))		
		<div class="input-prepend">
			<span class="add-on">{{LL('cms::form.page_display', CMSLANG)}}:</span>
			{{Form::select('blog_lang', Config::get('cms::settings.langs'), $lang, array('id' => 'change_lang', 'class' => 'span2'))}}
		</div>
		@else
		&nbsp;
		@endif
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_blog', CMSLANG)}}
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				@foreach (Config::get('cms::settings.langs') as $key => $value)
					<li>
						<a href="{{action('cms::blog@new', array($key))}}">
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
			<col width="66%">
			<col width="18%">
			<col width="16%">
			<thead>
				<tr>
					<th>{{LL('cms::label.blogname', CMSLANG)}}</th>
					<th>{{LL('cms::label.datetime_on', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">

				@forelse ($data->results as $blog)
				<tr class="post">
					<td>
						<i class="icon-star<?php if($blog->is_valid == 0) echo '-empty'; ?>"></i>
						{{HTML::span($blog->name, array('class' => 'pop-over', 'rel' => $blog->id, 'data-original-title' => LL('cms::title.popover_title_blog', CMSLANG)))}}
						{{HTML::span(LL('cms::label.url', CMSLANG).$blog->slug, array('class' => 'page_url block'))}}
					</td>
					<td>
						{{$blog->dt_on}}
					</td>
					<td>
						
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::blog@edit', array($blog->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>

							<div class="btn-group">
								<a href="#modal-delete-{{$blog->id}}" class="btn btn-mini btn-danger" data-toggle="modal">
									<i class="icon-trash icon-white"></i>
									{{LL('cms::button.delete', CMSLANG)}}
								</a>
							</div>
							
						</div>

						<div class="modal hide" id="modal-delete-{{$blog->id}}">
							{{Form::open(action('cms::blog@delete'), 'POST')}}
							{{Form::hidden('blog_id', $blog->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_blog', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{$blog->name}}</p>
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
					<td colspan="3">{{$data->next()}}</td>
				</tr>
				@endif
				
			</tbody>
		</table>

	</div>
</div>
