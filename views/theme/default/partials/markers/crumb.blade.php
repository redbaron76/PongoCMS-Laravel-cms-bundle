@if($crumbs)
<ul{{$options}}>
	
	<?php $c = 0 ?>

	@foreach($crumbs as $slug => $label)

		@if(strlen($separator) > 0 and $c == 0 and $first)
		<li class="separator">{{$separator}}</li>
		@endif

		<li{{CmsUtility::link_active($slug)}}>	

			<a href="{{SLUG($slug)}}">

				{{CmsUtility::string_style($label, 'allcapital')}}

			</a>

		</li>

		<?php $c++ ?>

		@if(strlen($separator) > 0 and $c < count($crumbs))
		<li class="separator">{{$separator}}</li>
		@endif

		@if(strlen($separator) > 0 and $c == count($crumbs) and $last)
		<li class="separator">{{$separator}}</li>
		@endif

	@endforeach

</ul>
@endif
