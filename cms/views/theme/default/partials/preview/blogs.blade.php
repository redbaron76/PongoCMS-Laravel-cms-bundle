@if($text)
	
	{{MARKER('[$CRUMB[{"separator":"&rsaquo;"}]]')}}

	<h2>{{$text->name}}</h2>

	<h6>{{$text->datetime_blog}} - {{$text->user->username}}</h6>

	<blockquote>{{Marker::decode($text->preview)}}</blockquote>

	{{Marker::decode($text->text)}}

@endif