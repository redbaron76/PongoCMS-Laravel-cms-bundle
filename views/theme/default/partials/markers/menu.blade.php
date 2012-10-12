@if($pages)
<ul{{$options}}>
	
	@foreach($pages as $page)
	<li{{CmsUtility::link_menu_active($page->slug)}}>

		<a href="{{SLUG($page->slug)}}">
			{{$page->name}}
		</a>
		@if($nested)
		{{View::make('cms::theme.'.THEME.'.partials.markers.menu_nested', array('pages' => CmsMenu::recursive_pages_menu($page->id, $mid)))}}
		@endif
	</li>
	@endforeach

</ul>
@endif