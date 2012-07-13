<ul{{$options}}>
	
	@forelse($files as $file)
	<li>
		@if($full)
			{{Marker::DOWNLOAD(array('file' => $file->name))}}
		@else		
			{{HTML::link($file->path, $file->name)}}
		@endif
	</li>
	@empty

	@endforelse

</ul>