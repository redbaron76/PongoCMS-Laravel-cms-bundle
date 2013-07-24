<tr rel="{{$file->id}}">
	<td>
		<a href="{{BASE.$file->path}}" class="thumbnail fancy" rel="tooltip" data-original-title="{{$file->name}}">							
			<img src="{{BASE.$file->thumb}}">							
		</a>
	</td>
	<td class="v-middle"><small>{{$file->name}}</small></td>
	<td>
		{{HTML::link('#', LL('cms::button.delete', CMSLANG), array('class' => 'btn btn-mini btn-danger pull-right list-delete', 'data-file' => $file->id, 'data-list' => $list_id, 'data-rel' => 'galleries'))}}
	</td>
</tr>