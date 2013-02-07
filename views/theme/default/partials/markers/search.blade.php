{{Form::open(URL::to_action('site@search'), 'POST', $options)}}

	<div class="input-append">

		<input type="text" name="q" placeholder="{{LL('cms::marker.search_placeholder')}}">

		<input type="hidden" name="source" value="{{$source}}">

		<button class="btn">{{LL('cms::marker.search')}}</button>

	</div>

{{Form::close()}}
