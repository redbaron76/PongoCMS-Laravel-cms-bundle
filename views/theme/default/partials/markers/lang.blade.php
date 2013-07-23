<ul{{$options}}>

	<?php

		$c = 0;

		if(!empty($exclude)) {

			$exclude_list = explode('-', $exclude);

			foreach ($exclude_list as $excl) {
				unset($langs[$excl]);
			}

		}

	?>

	@foreach($langs as $code => $lang)


			@if(count($langs) > 1 and strlen($separator) > 0 and $c == 0 and $first)
			<li class="separator">{{$separator}}</li>
			@endif

			<li{{CmsUtility::link_lang($code)}}>
				<a href="{{action('site@lang', array($code))}}">
					{{substr($lang, 0, 3)}}
				</a>
			</li>

			<?php $c++; ?>

			@if(count($langs) > 1 and strlen($separator) > 0 and $c < count($langs))
			<li class="separator">{{$separator}}</li>
			@endif

			@if(count($langs) > 1 and strlen($separator) > 0 and $c == count($langs) and $last)
			<li class="separator">{{$separator}}</li>
			@endif


	@endforeach
</ul>
