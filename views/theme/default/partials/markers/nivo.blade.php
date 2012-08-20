<div class="slider-wrapper theme-{{$theme}}">
	
	<div class="ribbon"></div>

	<div{{$options}}>

		@foreach($images as $key => $image)

			<?php

				$titles[$key] = ($caption and strlen($attr[$key]->filetexts[0]->caption) > 0) ? '#'.Str::random(10, 'alpha') : '';

			?>

			{{HTML::image($image->path, $attr[$key]->filetexts[0]->alt, array('width' => $image->w, 'height' => $image->h, 'title' => $titles[$key]))}}

		@endforeach

	</div>

	@if($caption)

		@foreach($images as $key => $image)

			@if(strlen($attr[$key]->filetexts[0]->caption) > 0)

				<div id="{{str_replace('#','',$titles[$key])}}" class="nivo-html-caption">
					{{$attr[$key]->filetexts[0]->caption}}
				</div>

			@endif

		@endforeach

	@endif

</div>
