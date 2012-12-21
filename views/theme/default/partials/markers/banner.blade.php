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

			<?php $img = ($wm) ? URL::to_action('cms::image@resize', array($image->w, $image->h, 'wm', $image->name))
								   : MEDIA_NAME($image->path, $thumb); ?>

			{{HTML::image($img, $alt)}}
			
		</a>
	</li>
	@endforeach

</ul>
