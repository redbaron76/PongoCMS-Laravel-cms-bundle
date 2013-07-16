<?php

	// CHECK PAGE HAS CHILDREN
	$has_child = false;
	foreach ($item as $obj) {
		if($page->id === $obj->parent_id) {
			$has_child = true;
			break;
		}
	}

	$attr = ((bool) $page->is_home) ? '' : 'sortable';
	$attr .= ($_active == $page->id) ? ' active' : '';	

	$options = HTML::attributes(array('class' => $attr));

?>

<li id="pag_{{$page->id}}" rel="{{$page->parent_id}}"{{$options}}>

	<div class="row-fluid">
		<div class="span8">
			
			@if($has_child)
			<button class="btn btn-mini toggle" rel="{{$page->id}}">+</button>
			@endif

			<i class="icon-star<?php if($page->is_valid == 0) echo '-empty'; ?>"></i>
			@if ((bool) $page->is_home)
			<i class="icon-home"></i>
			@endif
			@if ($page->access_level > 0)
			<i class="icon-lock"></i>
			@endif
			@if (empty($page->layout))
			<i class="icon-exclamation-sign"></i>
			@endif

			{{HTML::span($page->name, array('class' => 'pop-over', 'rel' => $page->id, 'data-original-title' => LL('cms::title.popover_title_page', CMSLANG)))}}

			{{HTML::span(LL('cms::label.url', CMSLANG).$page->slug, array('class' => 'page_url block'))}}

		</div>
		<div class="span4 clearfix">
			
			<div class="btn-toolbar pull-right">
				<div class="btn-group">
					<a href="{{action('cms::page@edit', array($page->id))}}" class="btn btn-mini">{{LL('cms::button.edit', CMSLANG)}}</a>
				</div>

				<div class="btn-group">
					<a href="{{action('cms::page@new_element', array($page->id))}}" class="btn btn-mini btn-primary">
						<i class="icon-plus icon-white"></i>
						{{LL('cms::form.page_element', CMSLANG)}}
					</a>
					<button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li>
							<span>
								<i class="icon-edit"></i>
								{{LL('cms::form.page_edit_elements', CMSLANG)}}
							</span>
						</li>
						<li class="divider"></li>
						@if (!is_null($page->elements))
							@forelse ($page->elements as $element)
							<li>
								<a href="{{action('cms::page@edit_element', array($page->id, $element->id))}}">
									<i class="icon-star<?php if($element->is_valid == 0) echo '-empty'; ?>"></i>
									<span class="label label-info">{{strtoupper($element->zone)}}</span>
									{{$element->label}}
								</a>
							</li>
							@empty
							<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
							@endforelse
						@else
						<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
						@endif
					</ul>
				</div>

				<div class="btn-group">
					<a href="#modal-delete-{{$page->id}}" class="btn btn-mini btn-danger" data-toggle="modal">
						<i class="icon-trash icon-white"></i>
						{{LL('cms::button.delete', CMSLANG)}}
					</a>
					<button class="btn btn-mini btn-danger dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li>
							<span>
								<i class="icon-trash"></i>
								{{LL('cms::form.page_delete_elements', CMSLANG)}}
							</span>
						</li>
						<li class="divider"></li>
						@if (!is_null($page->elements))
							@forelse ($page->elements as $element)
							<li>
								<a href="#element-delete-{{$page->id}}-{{$element->id}}" data-toggle="modal">
									<i class="icon-star<?php if($element->is_valid == 0) echo '-empty'; ?>"></i>
									<span class="label label-info">{{strtoupper($element->zone)}}</span>
									{{$element->label}}
								</a>
							</li>
							@empty
							<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
							@endforelse
						@else
						<li><a href="#">{{LL('cms::alert.element_empty', CMSLANG)}}</a></li>
						@endif
					</ul>
				</div>
				
			</div>

		</div>

	</div>
	
	{{ CmsPage::page_list_recursive($item, $page->id) }}

</li>