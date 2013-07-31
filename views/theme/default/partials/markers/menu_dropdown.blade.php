@if($pages)
<div {{$options}}>
  <ul class="nav">
  @foreach($pages as $page)
  @if(sizeof(CmsMenu::recursive_pages_menu($page->id, $mid))>0)
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">{{$page->name}}<b class="caret"></b></a>
	{{View::make('cms::theme.'.THEME.'.partials.markers.menu_nested_dropdown', array('pages' => CmsMenu::recursive_pages_menu($page->id, $mid)))}}
    </li>
    @else
    <li{{CmsUtility::link_menu_active($page->slug)}}>
		<a href="{{SLUG($page->slug)}}"{{CmsUtility::link_active($page->slug)}}>
			{{$page->name}}
		</a>
    </li>
    @endif
    @endforeach
  </ul>
</div>
@endif




