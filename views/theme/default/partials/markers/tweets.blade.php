<h3>{{$user}}'s latest tweets</h3>

<ul{{$options}}>

	@foreach($tweets->entry as $tweet)
	<li>
		<a href="http://twitter.com/{{$user}}">
			<img src="{{$tweet->link[1]->attributes()->href}}" class="pull-left">
		</a>		
		<p>
			{{$tweet->content}}
			<span>{{CmsUtility::relative_time($tweet->published)}}</span>
		</p>
		
	</li>
	@endforeach

</ul>