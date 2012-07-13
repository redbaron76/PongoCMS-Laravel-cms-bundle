<ul{{$options}}>
	@foreach($langs as $code => $lang)

		@if(substr_count($exclude, $code) == 0)
			<li{{CmsUtility::link_lang($code)}}>
				<a href="{{action('site@lang', array($code))}}">
					{{$code}}
				</a>
			</li>
		@endif

	@endforeach
</ul>