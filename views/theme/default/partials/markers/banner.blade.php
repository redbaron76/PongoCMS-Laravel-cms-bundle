<ul{{$options}}>
	
	@foreach($images as $key => $image)
	<li>
		
		<?php

			$target = ((bool) $image->pivot->is_blank) ? ' target="_blank"' : '';
			$wm = ((bool) $image->pivot->wm) ? 'wm' : 'no';
			$title = '';
			$alt = '';

			// GET TITLE AND ALT

			if(!empty($attr[$key]->filetexts)) {
				$title = (strlen($attr[$key]->filetexts[0]->title) > 0) ? ' title="'.$attr[$key]->filetexts[0]->title.'"' : '';
				$alt = (strlen($attr[$key]->filetexts[0]->alt) > 0) ? $attr[$key]->filetexts[0]->alt : '';
			}

			// CHECK IF W AND H

			$width = (is_null($w) and is_null($h)) ? $image->w : $w;
			$height = (is_null($h) and is_null($w)) ? $image->h : $h;

				
			// RESIZE IMAGE IF W AND H

			if(is_null($w) and is_null($h)) {

				$img = (strlen($thumb) > 0 and $wm == 'no') ?
				MEDIA_NAME($image->name, $thumb, true) :
				URL::to_action('cms::image@resize', array($width, $height, $wm, $image->name));

			} else {

				$img = URL::to_action('cms::image@thumb', array($width, $height, $wm, $image->name));

			}

			// CHECK LINK EXISTS

			$is_link = (strlen($image->pivot->url) > 0) ? true : false;

		?>

		@if($is_link)
		<a href="{{SLUG($image->pivot->url)}}"{{$target}}{{$title}}>
		@endif

			{{HTML::image($img, $alt)}}
			
		@if($is_link)
		</a>
		@endif

	</li>
	@endforeach

</ul>
