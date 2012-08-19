@if($pages)
<ul>
	
	@foreach($pages as $page)
	<li>
		<a href="{{SLUG($page->slug)}}"{{CmsUtility::link_active($page->slug)}}>
			{{$page->name}}
		</a>
	</li>
	@endforeach

</ul>
@endif