@if(!empty($list))
<ul class="unstyled">
	@foreach($list->results as $post)
	<li>
		<h2>
			<a href="{{SLUG(SLUG_FULL.$post->slug)}}">{{$post->name}}</a>
		</h2>
		
		<h6>{{$post->datetime_blog}} - {{$post->user->username}}</h6>

		{{Marker::decode($post->preview)}}

	</li>
	@endforeach
</ul>

{{$list->links()}}

@endif
