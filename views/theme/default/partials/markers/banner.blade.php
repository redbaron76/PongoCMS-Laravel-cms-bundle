<ul{{$options}}>
	
	@forelse($images as $image)
	<li>
		<?php $target = ((bool) $image->pivot->is_blank) ? ' target="_blank"' : '' ?>
		<a href="{{SLUG($image->pivot->url)}}"{{$target}}>
			{{HTML::image($image->path, $image->pivot->url, array('width' => $image->w, 'height' => $image->h))}}
		</a>
	</li>
	@empty
	<li></li>
	@endforelse

</ul>