<p>
	<strong>{{LL('cms::label.author', CMSLANG)}}:</strong> {{$author}}
</p>
<p>
	<strong>{{LL('cms::label.created_at', CMSLANG)}}:</strong> {{$created_at}}
</p>
<p>
	<strong>{{LL('cms::label.updated_at', CMSLANG)}}:</strong> {{$updated_at}}
</p>

<p class="hspace">
	<strong>{{LL('cms::label.language', CMSLANG)}}</strong>
	<span class="label label-info">{{strtoupper($lang)}}</span>
</p>

<p class="hspace">
	<strong>{{LL('cms::label.layout', CMSLANG)}}</strong>
</p>

@if(!empty($layout))
<p>
	<span class="label">{{strtoupper($header)}}</span>
	<span class="label label-warning">{{strtoupper($layout)}}</span>
	<span class="label">{{strtoupper($footer)}}</span>
</p>
@else
<p>
	<i class="icon-exclamation-sign"></i>
	{{LL('cms::label.layout_not_set', CMSLANG)}}
</p>
@endif

<p class="hspace">
	<strong>{{LL('cms::label.page_elements', CMSLANG)}}</strong>
</p>
<ul class="unstyled">
@forelse($elements as $element)
	<li>
		<i class="icon-star<?php if($element->is_valid == 0) echo '-empty'; ?>"></i>
		<span class="label label-info">{{strtoupper($element->zone)}}</span>
		{{$element->label}}
	</li>
@empty
	<li>{{LL('cms::label.page_no_elements', CMSLANG)}}</li>
@endforelse
</ul>

@if (!$is_valid)
<p class="hspace">
	<i class="icon-star-empty"></i>
	{{LL('cms::label.page_not_valid', CMSLANG)}}
</p>
@endif

<p class="hspace">
	<strong>{{LL('cms::label.page_relations', CMSLANG)}}</strong>
</p>
<ul>
@forelse($pagerels as $page)
	<li>{{$page->name}}</li>
@empty
	<li>{{LL('cms::label.page_no_relations', CMSLANG)}}</li>
@endforelse
</ul>