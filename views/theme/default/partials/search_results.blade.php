<div class="{{Config::get('cms::theme.ele_class')}}">

	<h2>{{LL('cms::marker.search_results_title')}}</h2>

	<h5>{{LL('cms::marker.search_found', SITE_LANG, array('q' => $q))}}</h5>

	<ul class="search_results unstyled">

		@forelse($results->results as $result)
		<li>
			
			<strong>{{$result['source']}}</strong> &gt; <a href="{{SLUG($result['slug'])}}">{{$result['title']}} </a>

			@if(!empty($result['descr']))
			<p>{{$result['descr']}}</p>
			@endif

		</li>
		@empty
		<li>
			<p>{{LL('cms::marker.search_not_found')}}</p>
		</li>
		@endforelse

	</ul>

	{{$results->appends(array('q' => $q, 'source' => $source))->links()}}

</div>
