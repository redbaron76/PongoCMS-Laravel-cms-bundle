<ul{{$options}}>
	
	@forelse($files as $key => $file)
	<li>
		<?php

			$label = $file->name;

			if(!empty($file->filetexts[0])) {
			 	$title = (strlen($file->filetexts[0]->title) > 0) ? ' title="'.$file->filetexts[0]->title.'"' : '';
			 	$label = (strlen($file->filetexts[0]->label) > 0) ? $file->filetexts[0]->label : $file->name;
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
