<p>
	<strong>{{LL('cms::label.datetime_on', CMSLANG)}}:</strong> {{$datetime_on}}
</p>
<p>
	<strong>{{LL('cms::label.datetime_off', CMSLANG)}}:</strong> {{$datetime_off}}
</p>

<p class="hspace">
	<strong>{{LL('cms::label.author', CMSLANG)}}:</strong> {{$author}}
</p>
<p>
	<strong>{{LL('cms::label.created_at_o', CMSLANG)}}:</strong> {{$created_at}}
</p>
<p>
	<strong>{{LL('cms::label.updated_at', CMSLANG)}}:</strong> {{$updated_at}}
</p>

<p class="hspace">
	<strong>{{LL('cms::label.language', CMSLANG)}}</strong>
	<span class="label label-info">{{strtoupper($lang)}}</span>
</p>

@if (!$is_valid)
<p class="hspace">
	<i class="icon-star-empty"></i>
	{{LL('cms::label.page_not_valid', CMSLANG)}}
</p>
@endif

<p class="hspace">
	<strong>{{LL('cms::label.page_owners', CMSLANG)}}</strong>
</p>
<ul>
@forelse($pagerels as $page)
	<li>{{$page->name}}</li>
@empty
	<li>{{LL('cms::label.page_no_relations', CMSLANG)}}</li>
@endforelse
</ul>

<p class="hspace">
	<strong>{{LL('cms::label.blog_links', CMSLANG)}}</strong>
</p>
<ul>
@forelse($blogrels as $blog)
	<li>{{$blog->name}}</li>
@empty
	<li>{{LL('cms::label.page_no_link', CMSLANG)}}</li>
@endforelse
</ul>