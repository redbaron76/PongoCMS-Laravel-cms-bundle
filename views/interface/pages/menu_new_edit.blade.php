<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::menu')}}" class="btn btn-inverse pull-right">
			<i class="icon-arrow-left icon-white"></i>
			{{LL('cms::button.back', CMSLANG)}}
		</a>
	</div>
</div>

<div class="row space">
	<div class="span12">

		<div class="row">
			<div class="span2 side tabbable tabs-left">
				
				<ul class="nav nav-tabs">
					<li class="active"><a href="#menu" data-toggle="tab">{{LL('cms::form.menu', CMSLANG)}}</a></li>
					<li{{DISABLED($menu_id)}}><a href="#order" data-toggle="tab">{{LL('cms::form.menu_order', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- TEXTS FORM TAB -->
					<div class="tab-pane active" id="menu">
						{{Form::open(action('cms::ajax_menu@save_menu'), 'POST', array('class' => 'form-vertical', 'id' => 'form_menu')) . "\n"}}
						{{Form::hidden('menu_id', $menu_id, array('class' => 'menu_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.menu_legend', CMSLANG)}}</legend>

									<div class="control-group">
										{{Form::label('banner_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
										<div class="controls">
											{{Form::hidden('menu_lang', $menu_lang, array('id' => 'menu_lang')) . "\n"}}
											{{HTML::span(CONF('cms::settings.langs', $menu_lang), array('class' => 'label label-warning')) . "\n"}}
										</div>
									</div>
									<div class="control-group" rel="menu_name">
										{{Form::label('menu_name', LL('cms::form.menuname', CMSLANG), array('class' => 'control-label')) . "\n"}}
										<div class="controls">
											{{Form::text('menu_name', $menu_name, array('class' => 'span4', 'id' => 'menu_name')) . "\n"}}
										</div>
									</div>
									<div class="control-group">
										{{Form::label('parent_start', LL('cms::form.menu_parent_start', CMSLANG), array('class' => 'control-label')) . "\n"}}
										<div class="controls">
											{{Form::select('parent_start', $menu_parent_start, $menu_parent_start_selected, array('id' => 'menu_parent_start', 'class' => 'span7')) . "\n"}}
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<label class="checkbox">
												{{Form::checkbox('is_nested', 1, $menu_is_nested, array('id' => 'menu_is_nested'))}}
												{{LL('cms::form.menu_is_nested', CMSLANG)}}
											</label>
										</div>
									</div>
									


									<ul class="unstyled page-list space">
										
										<?php $c = 0; ?>
										
										@forelse($menu_pages as $page)

											<?php if($c == 0) $lang = $page->lang; ?>
											<?php
												if($page->lang != $lang) {
											 		$lang = $page->lang;
											 		$c = 0;
											 	}
											?>

											@if($c == 0)										
											<li class="divider">
												<h4>{{CONF('cms::settings.langs', $lang)}}</h4>
											</li>
											@endif


											<li>
												
												<div class="control-group">
													<div class="controls">
														<label class="checkbox">
															<?php 
																if(!empty($page->menus)) {															
																	foreach($page->menus as $menu) {
																		$valid = ($menu->pivot->cmsmenu_id == $menu_id) ? true : false;
																		if($menu->pivot->cmsmenu_id == $menu_id) break;
																	}
																} else {
																	$valid = false;
																}															
															?>
															{{Form::checkbox('page_id[]', $page->id, $valid)}}
															@for ($i=0; $i < substr_count($page->slug, '/') - 1; $i++)
															<i class="icon-chevron-right"></i>
															@endfor
															{{$page->name}}
															{{HTML::span(LL('cms::label.url', CMSLANG).$page->slug, array('class' => 'page_url block'))}}
														</label>
													</div>
												</div>

											</li>
											
											<?php $c++;	?>

										@empty
											<li>{{LL('cms::alert.list_empty', CMSLANG)}}</li>
										@endforelse
									</ul>

								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_menu">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::menu', array($menu_lang))}}" class="btn btn-danger save_form" rel="form_menu">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::menu')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>

							</fieldset>
						{{Form::close()}}
					</div>

					<div class="tab-pane" id="order">

						<legend>{{LL('cms::form.menu_legend_order', CMSLANG)}}</legend>

						<ul class="sortable">

						@forelse ($pages as $page)
							<li id="{{$menu_id}}_{{$page->id}}">
								<a href="#" class="btn">
									<i class="icon-resize-vertical"></i>
									{{$page->name}}
								</a>
							</li>
						@empty
							<li>
								{{LL('cms::alert.list_empty', CMSLANG)}}
							</li>
						@endforelse
	
						</ul>

					</div>

				</div>

			</div>
		</div>

	</div>
</div>
