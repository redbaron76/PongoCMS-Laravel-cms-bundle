<ul{{$options}}>
	
	@foreach($images as $key => $image)
	<li>
		
		<?php $target = ((bool) $image->pivot->is_blank) ? ' target="_blank"' : '' ?>
		<?php $title = (count($attr[$key]->filetexts[0]->title) > 0) ? ' title="'.$attr[$key]->filetexts[0]->title.'"' : '' ?>

		<a href="{{SLUG($image->pivot->url)}}"{{$target}}{{$title}}>

			{{HTML::image($image->path, $attr[$key]->filetexts[0]->alt, array('width' => $image->w, 'height' => $image->h))}}
			
		</a>
	</li>
	@endforeach

</ul>
