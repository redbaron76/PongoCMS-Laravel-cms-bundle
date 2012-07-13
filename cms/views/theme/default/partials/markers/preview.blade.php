@if(!empty($list))
<ul>
	@foreach($list->results as $post)
	<li>
		<a href="{{SLUG_FULL.$post->slug}}">
			{{$post->name}}
		</a>
		<p>
			{{Marker::decode($post->preview)}}
		</p>
	</li>
	@endforeach
</ul>
<div class="pagination">
	{{$list->links()}}
</div>
@endif