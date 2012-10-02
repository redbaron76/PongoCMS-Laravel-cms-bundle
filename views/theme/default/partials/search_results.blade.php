<h1>Ricerca</h1>

<ul class="unstyled horiz crumb">
	<li>
		<a href="/">home</a>
	</li>
	<li class="separator">|</li>
	<li class="selected">
		<a href="{{URL::current()}}">ricerca</a>
	</li>
</ul>

<div class="search elements">

	<div class="element">

		<h2>Risultati della ricerca: "{{$q}}"</h2>

		<ul class="unstyled">

			@forelse($results->results as $result)
			<li>

				<h3>{{$result['source']}} &gt; 
					<a href="{{SLUG($result['slug'])}}">{{$result['title']}}</a>
				</h3>

				@if(!empty($result['descr']))
				<p>{{$result['descr']}}</p>
				@endif

			</li>
			@empty
			<li>
				<h3>Nessun risultato trovato</h3>
			</li>
			@endforelse

		</ul>

		{{$results->appends(array('q' => $q, 'source' => $source))->links()}}

	</div>
	
</div>