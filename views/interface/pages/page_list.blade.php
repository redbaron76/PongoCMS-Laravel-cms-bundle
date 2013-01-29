<div class="row">
	<div class="span4">
		<h2>{{LL('cms::title.pages', CMSLANG)}}</h2>
	</div>
	<div class="span6 toright">
		@if (!empty($lang))
		<div class="input-prepend">
			<span class="add-on">{{LL('cms::form.page_display', CMSLANG)}}:</span>
			{{Form::select('page_lang', Config::get('cms::settings.langs'), $lang, array('id' => 'change_lang', 'class' => 'span2'))}}
		</div>
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
						<a href="{{URL::to_action('cms::page@new', array($key))}}">
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
	<div class="span12" id="page_list">
		@if(!empty($data))

			{{ CmsPage::page_list_recursive($data) }}

			@foreach($data as $page)

				@if (!is_null($page->elements))

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
							<p>{{$element->label}}</p>
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
						<label class="checkbox">
							{{Form::checkbox('force_delete', 1, null)}}
							{{LL('cms::form.modal_page_force_delete', CMSLANG)}}
						</label>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
						{{Form::submit(LL('cms::button.delete', CMSLANG), array('class' => 'btn btn-danger'))}}
					</div>
					{{Form::close()}}
				</div>

			@endforeach

		@else
		<p>{{LL('cms::alert.list_empty', CMSLANG)}}</p>
		@endif

	</div>
</div>
