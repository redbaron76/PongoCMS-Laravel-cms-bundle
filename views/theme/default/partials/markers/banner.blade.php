<ul{{$options}}>
	
	@foreach($images as $key => $image)
	<li>
		
		<?php

			$target = ((bool) $image->pivot->is_blank) ? ' target="_blank"' : '';
			$title = '';
			$alt = '';

			if(!empty($attr[$key]->filetexts)) {
				$title = (strlen($attr[$key]->filetexts[0]->title) > 0) ? ' title="'.$attr[$key]->filetexts[0]->title.'"' : '';
				$alt = (strlen($attr[$key]->filetexts[0]->alt) > 0) ? $attr[$key]->filetexts[0]->alt : '';
			}

		?>

		<a href="{{SLUG($image->pivot->url)}}"{{$target}}{{$title}}>

			{{HTML::image($image->path, $alt, array('width' => $image->w, 'height' => $image->h))}}
			
		</a>
	</li>
	@endforeach

</ul>
