@forelse ($media as $key => $file)
	@if(!isset($file->$rel))
	<tr rel="{{$file->id}}">
		<td>
			<a href="#" class="thumbnail">
				<img src="{{BASE.$file->thumb}}" width="50" width="50" rel="{{$file->id}}">
			</a>
		</td>
		<td>
			<strong>{{$file->name}}</strong>
			{{HTML::span($file->path, array('class' => 'page_url block path', 'rel' => $file->id))}}
		</td>
		<td>
			
			<div class="btn-group pull-right">
				<a class="btn btn-primary dropdown-toggle list-insert" href="#" data-file="{{$file->id}}" data-list="{{$lid}}" data-rel="{{$rel}}">
					{{LL('cms::button.insert', CMSLANG)}}
				</a>
			</div>

		</td>
	</tr>
	@endif
@empty
<tr>
	<td colspan="3">{{LL('cms::alert.list_media_empty', CMSLANG)}}</td>
</tr>
@endforelse