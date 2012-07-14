@if($elements)
<ul{{$options}}>
	
	@foreach($elements as $element)
	<li>

		<a href="{{CmsUtility::parse_slug($slug)}}#{{$element->name}}">
			{{$element->label}}
		</a>

	</li>
	@endforeach

</ul>
@endif