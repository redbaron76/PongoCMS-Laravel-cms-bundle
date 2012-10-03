<div class="row">
	<div class="span4">
		<h2>{{LL('cms::title.pages', CMSLANG)}}</h2>
	</div>
	<div class="span6 toright">		
		@if (!empty($lang))
		<a href="{{action('cms::page@sitemap')}}" class="btn btn-danger v-top">Site map</a>
		{{Form::select('page_lang', Config::get('cms::settings.langs'), $lang, array('id' => 'change_lang'))}}
		@else
		&nbsp;
		@endif
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_page', CMSLANG)}}
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				@foreach (Config::get('cms::settings.langs') as $key => $value)
					<li>
						<a href="{{action('cms::page@new', array($key))}}">
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
			<col width="70%">
			<col width="30%">
			<thead>
				<tr>
					<th>{{LL('cms::label.pagename', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">

				@forelse ($data->results as $page)
				<tr class="post">
					<td>
						<i class="icon-star<?php if($page->is_valid == 0) echo '-empty'; ?>"></i>
						@if ($page->access_level > 0)
						<i class="icon-lock"></i>
						@endif
						@if (empty($page->layout))
						<i class="icon-exclamation-sign"></i>
						@endif
						{{HTML::span($page->name, array('class' => 'pop-over', 'rel' => $page->id, 'data-original-title' => LL('cms::title.popover_title_page', CMSLANG)))}}
						@if ($page->is_home == 1)
						{{HTML::span('home', array('class' => 'label label-info'))}}
						@endif
						{{HTML::span(LL('cms::label.url', CMSLANG).$page->slug, array('class' => 'page_url block'))}}
					</td>
					<td>
						
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::page@edit', array($page->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>

							<div class="btn-group">
								<a href="{{action('cms::page@new_element', array($page->id))}}" class="btn btn-mini btn-primary">
									<i class="icon-plus icon-white"></i>
									{{LL('cms::form.page_element', CMSLANG)}}
								</a>
								<button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu pull-right">
									<li>
										<span>
											<i class="icon-edit"></i>
											{{LL('cms::form.page_edit_elements', CMSLANG)}}
										</span>
									</li>
									<li class="divider"></li>
									@if (!empty($page->elements))
										@forelse ($page->elements as $element)
										<li>
											<a href="{{action('cms::page@edit_element', array($page->id, $element->id))}}">												
												<i class="icon-star<?php if($element->is_valid == 0) echo '-empty'; ?>"></i>
												{{$element->name}}
												<span class="badge-mini badge-info">{{strtoupper($element->zone)}}</span>
											</a>
										</li>
										@empty
										<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
										@endforelse
									@else
									<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
									@endif
								</ul>
							</div>

							<div class="btn-group">
								<a href="#modal-delete-{{$page->id}}" class="btn btn-mini btn-danger" data-toggle="modal">
									<i class="icon-trash icon-white"></i>
									{{LL('cms::button.delete', CMSLANG)}}
								</a>
								<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu pull-right">
									<li>										
										<span>
											<i class="icon-trash"></i>
											{{LL('cms::form.page_delete_elements', CMSLANG)}}
										</span>
									</li>
									<li class="divider"></li>
									@if (!empty($page->elements))
										@forelse ($page->elements as $element)
										<li>
											<a href="#element-delete-{{$page->id}}-{{$element->id}}" data-toggle="modal">
												<i class="icon-star<?php if($element->is_valid == 0) echo '-empty'; ?>"></i>
												{{$element->name}}
												<span class="badge-mini badge-info">{{strtoupper($element->zone)}}</span>
											</a>
										</li>
										@empty
										<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
										@endforelse
									@else
									<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
									@endif
								</ul>
							</div>
							
						</div>

						@if (!empty($page->elements))

							@foreach ($page->elements as $element)
							<div class="modal hide" id="element-delete-{{$page->id}}-{{$element->id}}">
								{{Form::open(action('cms::page@delete_element', array($element->id)), 'POST')}}
								{{Form::hidden('element_id', $element->id)}}
								{{Form::hidden('page_id', $page->id)}}
								<div class="modal-header">
									<button class="close" data-dismiss="modal">×</button>
									<h3>{{LL('cms::form.modal_title_element', CMSLANG)}}</h3>
								</div>
								<div class="modal-body">
									<p>{{$element->name}}</p>
								</div>
								<div class="modal-footer">
									<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
									{{Form::submit(LL('cms::button.delete', CMSLANG), array('class' => 'btn btn-danger'))}}
								</div>
								{{Form::close()}}
							</div>
							@endforeach

						@endif

						<div class="modal hide" id="modal-delete-{{$page->id}}">
							{{Form::open(action('cms::page@delete_page'), 'POST')}}
							{{Form::hidden('page_id', $page->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_page', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{$page->name}}</p>
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

				<tr class="navigation">
					<td colspan="3">{{$data->next()}}</td>
				</tr>
				
			</tbody>
		</table>

	</div>
</div>