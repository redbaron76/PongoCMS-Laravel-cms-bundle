<ul{{$options}}>
	
	@forelse($images as $image)
	<li>
		{{Marker::THUMB(array('file' => $image->name, 'thumb' => $thumb, 'wm' => $wm))}}
	</li>
	@empty

	@endforelse

</ul>