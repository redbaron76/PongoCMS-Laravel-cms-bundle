<table class="table fixed v-middle">
	<col width="30%">
	<col width="50%">
	<col width="20%">
	<tbody>
		
		@forelse(Config::get('cms::theme.sample') as $file_name => $descr)

		<tr>
			<td>
				<strong>{{$file_name}}</strong>
			</td>
			<td>
				{{$descr}}
			</td>
			<td class="toright">
				<a href="#" class="btn btn-primary as_sample" data-filename="{{$file_name}}">
					{{LL('cms::button.insert', CMSLANG)}}
				</a>
			</td>
		</tr>

		@empty
		<tr>
			<td colspan="3">{{LL('cms::alert.list_media_empty', CMSLANG)}}</td>
		</tr>
		@endforelse

	</tbody>
</table>