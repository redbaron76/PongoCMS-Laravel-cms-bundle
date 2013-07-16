<p>
	<strong>{{LL('cms::label.size', CMSLANG)}}:</strong> {{MEDIA_SIZE($size, 'KB')}}
</p>

@if($is_image)
<p class="hspace">
	<strong>{{LL('cms::label.width', CMSLANG)}}:</strong> {{$w}}px
</p>
<p>
	<strong>{{LL('cms::label.height', CMSLANG)}}:</strong> {{$h}}px
</p>
@endif

<p class="hspace">
	<strong>{{LL('cms::label.created_at_o', CMSLANG)}}:</strong> {{$created_at}}
</p>
<p>
	<strong>{{LL('cms::label.updated_at', CMSLANG)}}:</strong> {{$updated_at}}
</p>

<p class="hspace">
	<strong>{{LL('cms::label.availability', CMSLANG)}}</strong>
</p>
<ul>
@forelse($pagerels as $page)
	<li>{{$page->name}}</li>
@empty
	<li>{{LL('cms::label.page_no_relations', CMSLANG)}}</li>
@endforelse
</ul>