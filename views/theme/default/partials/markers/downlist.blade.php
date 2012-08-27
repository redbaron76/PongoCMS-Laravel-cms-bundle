<ul{{$options}}>
	
	@forelse($files as $key => $file)
	<li>
		<?php

			$label = $file->name;

			if(!empty($file->filetexts[$key])) {
			 	$title = (strlen($file->filetexts[$key]->title) > 0) ? ' title="'.$file->filetexts[$key]->title.'"' : '';
			 	$label = (strlen($file->filetexts[$key]->label) > 0) ? $file->filetexts[$key]->label : $file->name;
			}

		?>

		@if($full)
			{{Marker::DOWNLOAD(array('file' => $file->name, 'label' => $label))}}
		@else		
			{{HTML::link($file->path, $label)}}
		@endif

	</li>
	@empty

	@endforelse

</ul>