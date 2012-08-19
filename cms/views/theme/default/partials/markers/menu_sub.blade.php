@if($elements)
<ul{{$options}}>
	
	@foreach($elements as $element)
	<li>

		<a href="{{SLUG($slug)}}#{{$element->name}}">
			{{$element->label}}
		</a>

	</li>
	@endforeach

</ul>
@endif