@if(!empty($list))

<ul class="unstyled">
	
	@foreach($list as $post)

	<li>
		<h2>
			<a href="{{SLUG(SLUG_FULL.$post->pages[0]->slug.$post->slug)}}">{{$post->name}}</a>
		</h2>
		
		<h6>{{$post->datetime_blog}} - {{$post->user->username}}</h6>

		{{TEXTPREVIEW($post,250)}}

	</li>
	
	@endforeach

</ul>

@endif
