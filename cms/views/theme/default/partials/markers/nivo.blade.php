<div class="slider-wrapper theme-strato">
    
    <div class="ribbon"></div>

	<div{{$options}}>

		@foreach($images as $image)
			{{HTML::image($image->path, $image->pivot->url, array('width' => $image->w, 'height' => $image->h))}}
		@endforeach

	</div>

</div>