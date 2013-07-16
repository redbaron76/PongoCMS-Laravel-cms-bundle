<div class="row">
	<div class="span4">
		<h2>{{LL('cms::title.menus', CMSLANG)}}</h2>
	</div>
	<div class="span6 toright">
		@if (!empty($lang))
		<div class="input-prepend">
			<span class="add-on">{{LL('cms::form.page_display', CMSLANG)}}:</span>
			{{Form::select('menu_lang', Config::get('cms::settings.langs'), $lang, array('id' => 'change_lang', 'class' => 'span2'))}}
		</div>
		@else
		&nbsp;
		@endif
	</div>
	<div class="span2">
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-plus icon-white"></i>
				{{LL('cms::button.new_menu', CMSLANG)}}
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				@foreach (Config::get('cms::settings.langs') as $key => $value)
					<li>
						<a href="{{action('cms::menu@new', array($key))}}">
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
			<col width="15%">
			<col width="15%">
			<thead>
				<tr>
					<th>{{LL('cms::label.menuname', CMSLANG)}}</th>
					<th>{{LL('cms::label.menunested', CMSLANG)}}</th>
					<th>{{LL('cms::label.actions', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody class="listing">
				@forelse ($data as $menu)
				<tr>
					<td>{{$menu->name}}</td>
					<td>{{((bool) $menu->is_nested) ? LL('cms::label.yes', CMSLANG):LL('cms::label.no', CMSLANG)}}</td>
                    <td>

                    	<div class="btn-toolbar">
							<div class="btn-group">
								<a href="{{action('cms::menu@edit', array($menu->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							<div class="btn-group">
								<a href="#modal-delete-{{$menu->id}}" class="btn btn-mini btn-danger" data-toggle="modal">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
						</div>

						<div class="modal hide" id="modal-delete-{{$menu->id}}">
							{{Form::open(action('cms::menu@delete'), 'POST')}}
							{{Form::hidden('menu_id', $menu->id)}}
							<div class="modal-header">
								<button class="close" data-dismiss="modal">×</button>
								<h3>{{LL('cms::form.modal_title_menu', CMSLANG)}}</h3>
							</div>
							<div class="modal-body">
								<p>{{$menu->name}}</p>
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