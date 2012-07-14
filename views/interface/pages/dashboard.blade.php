<div class="row">
	<div class="span4">
		<h2>{{LL('cms::title.dashboard', CMSLANG)}}</h2>
	</div>
	<div class="span8">
		<div class="btn-toolbar pull-right">
			<div class="btn-group">
				<a href="{{action('cms::dashboard@db_backup')}}" class="btn btn-inverse">
					<i class="icon-download-alt icon-white"></i>
					{{LL('cms::button.backup_database', CMSLANG)}}
				</a>
			</div>
			<div class="btn-group">
				<a class="btn btn-danger dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-trash icon-white"></i>
					{{LL('cms::button.delete_cache', CMSLANG)}}
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					@foreach (Config::get('cms::settings.cache_pattern') as $key => $value)
						<li>
							<a href="{{action('cms::dashboard@delete_cache', array($key))}}">
								<i class="icon-chevron-right"></i>
								{{LL('cms::label.cache_'.$value, CMSLANG)}}
							</a>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>

@if(CONF('cms::settings.analytics', 'profile_id'))
<div class="row space">
	<div class="span12">
		<h4>{{LL('cms::title.analytics', CMSLANG)}}</h4>
		<div id="placeholder" style="width: 940px; height: 300px;" class="space"></div>
	</div>
</div>
@endif

<div class="row space">
	<div class="span12">
		<h4>{{LL('cms::title.sitestats', CMSLANG)}}</h4>
		<table class="table table-striped fixed v-middle space">
			<col width="20%">
			<col width="20%">
			<col width="20%">
			<col width="20%">
			<col width="20%">
			<thead>
				<tr>
					<th>{{LL('cms::label.file_size', CMSLANG)}}</th>
					<th>{{LL('cms::label.image_size', CMSLANG)}}</th>
					<th>{{LL('cms::label.thumb_size', CMSLANG)}}</th>
					<th>{{LL('cms::label.cache_size', CMSLANG)}}</th>
					<th>{{LL('cms::label.total_size', CMSLANG)}}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{MEDIA_SIZE($files, 'MB')}}</td>
					<td>{{MEDIA_SIZE($images, 'MB')}}</td>
					<td>{{MEDIA_SIZE($thumbs, 'MB')}}</td>
					<td>{{MEDIA_SIZE($cache, 'MB')}}</td>
					<td>{{MEDIA_SIZE($total, 'MB')}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>