<div class="slider-wrapper theme-{{$theme}}">
	
	<div class="ribbon"></div>

	<div{{$options}}>

		@foreach($images as $key => $image)

			<?php

				$target = ((bool) $image->pivot->is_blank) ? ' target="_blank"' : '';
				$title = '';
				$alt = '';

				if(!empty($attr[$key]->filetexts)) {

					$titles[$key] = ($caption and strlen($attr[$key]->filetexts[0]->caption) > 0) ? '#'.Str::random(10, 'alpha') : '';
					$title = (strlen($attr[$key]->filetexts[0]->title) > 0) ? ' title="'.$attr[$key]->filetexts[0]->title.'"' : '';
					$alt = (strlen($attr[$key]->filetexts[0]->alt) > 0) ? $attr[$key]->filetexts[0]->alt : '';

				} else {

					$titles[$key] = '';

				}

			?>

			@if(strlen($image->pivot->url) > 0)
			<a href="{{SLUG($image->pivot->url)}}"{{$target}}{{$title}}>
			@endif

				{{HTML::image($image->path, $alt, array('width' => $image->w, 'height' => $image->h, 'title' => $titles[$key]))}}

			@if(strlen($image->pivot->url) > 0)
			</a>
			@endif

		@endforeach

	</div>

	@if($caption and !empty($titles))

		@foreach($images as $key => $image)

			@if(!empty($attr[$key]->filetexts) and strlen($attr[$key]->filetexts[0]->caption) > 0)

				<div id="{{str_replace('#','',$titles[$key])}}" class="nivo-html-caption">
					{{$attr[$key]->filetexts[0]->caption}}
				</div>

			@endif

		@endforeach

	@endif

</div>