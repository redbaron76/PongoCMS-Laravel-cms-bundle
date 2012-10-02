{{Form::open(URL::to_action('site@search'), 'POST', $options)}}

	<input type="text" name="q" placeholder="Cerca">
	
	<input type="hidden" name="source" value="{{$source}}">

	<button></button>

{{Form::close()}}