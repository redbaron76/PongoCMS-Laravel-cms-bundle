@forelse ($media as $file)
<tr>
	<td>
		<a href="#" class="thumbnail">
			<img src="{{BASE.$file->thumb}}" width="50" width="50">
		</a>
	</td>
	<td>
		<strong>{{$file->name}}</strong>
		{{HTML::span($file->path, array('class' => 'page_url block path', 'rel' => $file->id))}}
	</td>
	<td>
		
		<div class="btn-group pull-right">
			<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
				{{LL('cms::button.insert', CMSLANG)}}
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
			@if ((bool) $file->is_image)
				<li>
					<a href="#" class="as_html" data-path="{{$file->path}}" data-extension="{{$file->ext}}" data-filename="{{$file->name}}" data-tag="img">
						<i class="icon-picture"></i>
						{{LL('cms::button.as_picture', CMSLANG)}}
					</a>
				</li>
				@foreach(Config::get('cms::theme.thumb') as $key => $value)
				<li>
					<a href="#" class="as_html" data-path="{{MEDIA_NAME($file->path, $value['suffix'])}}" data-extension="{{$file->ext}}" data-filename="{{$file->name}}" data-tag="img">
						<i class="icon-picture"></i>
						{{LL('cms::button.as_picture', CMSLANG)}} - ({{$key}})
					</a>
				</li>
				@endforeach
				<li>
					<a href="#" class="as_text" data-path="{{$file->path}}" data-extension="{{$file->ext}}" data-filename="{{$file->name}}" data-tag="IMAGE">
						<i class="icon-th-list"></i>
						{{LL('cms::button.as_marker_image', CMSLANG)}}
					</a>
				</li>
				<li>
					<a href="#" class="as_text" data-path="{{$file->path}}" data-extension="{{$file->ext}}" data-filename="{{$file->name}}" data-tag="THUMB">
						<i class="icon-th-list"></i>
						{{LL('cms::button.as_marker_thumb', CMSLANG)}}
					</a>
				</li>
			@else
				<li>
					<a href="#" class="as_text" data-path="{{$file->path}}" data-extension="{{$file->ext}}" data-filename="{{$file->name}}" data-tag="DOWNLOAD">
						<i class="icon-download"></i>
						{{LL('cms::button.as_marker_download', CMSLANG)}}
					</a>
				</li>
			@endif													
				
			</ul>
		</div>

	</td>
</tr>
@empty
<tr>
	<td colspan="3">{{LL('cms::alert.list_media_empty', CMSLANG)}}</td>
</tr>
@endforelse
