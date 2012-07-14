@if($crumbs)
<ul{{$options}}>
	
	<?php $c = 0 ?>

	@foreach($crumbs as $slug => $label)
	<li>

		@if(strlen($separator) > 0 and $c == 0 and $first)
		<span>{{$separator}}</span>
		@endif

		<a href="{{$slug}}"{{CmsUtility::link_active($slug)}}>

			{{$label}}

		</a>

		<?php $c++ ?>

		@if(strlen($separator) > 0 and $c < count($crumbs))
		<span>{{$separator}}</span>
		@endif

		@if(strlen($separator) > 0 and $c == count($crumbs) and $last)
		<span>{{$separator}}</span>
		@endif

	</li>
	@endforeach

</ul>
@endif