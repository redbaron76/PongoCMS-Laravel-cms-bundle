@if($pages)
<ul class='dropdown-menu'>
	@foreach($pages as $page)
	<li dn>
		<a href="{{SLUG($page->slug)}}"{{CmsUtility::link_active($page->slug)}}>
			{{$page->name}}
		</a>
	</li dn>
	@endforeach
</ul dn>
@endif


