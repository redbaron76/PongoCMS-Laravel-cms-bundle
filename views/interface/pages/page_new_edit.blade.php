<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::page')}}" class="btn btn-inverse pull-right">
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
					<li class="active"><a href="#settings" data-toggle="tab">{{LL('cms::button.page_settings', CMSLANG)}}</a></li>
					<li{{DISABLED($page_id)}}><a href="#design" data-toggle="tab">{{LL('cms::button.page_design', CMSLANG)}}</a></li>
					<li{{DISABLED($page_id)}}><a href="#seo" data-toggle="tab">{{LL('cms::button.page_seo', CMSLANG)}}</a></li>
					<li{{DISABLED($page_id)}}><a href="#media" data-toggle="tab">{{LL('cms::button.page_media', CMSLANG)}}</a></li>
					<li{{DISABLED($page_id)}}><a href="#preview" data-toggle="tab">{{LL('cms::button.page_abstract', CMSLANG)}}</a></li>
					<li{{DISABLED($page_id)}}><a href="#order" data-toggle="tab">{{LL('cms::form.subpage_order', CMSLANG)}}</a></li>
					<li{{DISABLED($page_id)}}><a href="#relations" data-toggle="tab">{{LL('cms::form.page_relation', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- SETTINGS FORM -->
					<div class="tab-pane active" id="settings">
						{{Form::open(action('cms::ajax_page@save_settings'), 'POST', array('class' => 'form-vertical', 'id' => 'form_settings')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.page_legend_settings', CMSLANG)}}</legend>
								<div class="control-group">
									{{Form::label('page_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::hidden('page_lang', $page_lang, array('id' => 'page_lang')) . "\n"}}
										{{HTML::span(CONF('cms::settings.langs', $page_lang), array('class' => 'label label-warning')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="page_name">
									{{Form::label('page_name', LL('cms::form.page_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('page_name', $page_name, array('class' => 'span4', 'id' => 'page_name')) . "\n"}}
									</div>
								</div>
								<div class="control-group">
									{{Form::label('page_parent', LL('cms::form.page_parent', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('page_parent', $page_parent, $page_parent_selected, array('id' => 'page_parent', 'class' => 'span7')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="page_slug">
									{{Form::label('page_slug', LL('cms::form.page_slug', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on" rel="page_slug">{{$page_parent_slug}}/</span>
											{{Form::text('page_slug', $page_slug, array('class' => 'span7', 'id' => 'page_slug')) . "\n"}}
											{{Form::hidden('page_parent_slug', $page_parent_slug, array('id' => 'page_parent_slug')) . "\n"}}
										</div>
									</div>
								</div>
								<div class="control-group" rel="page_extra">
									{{Form::label('page_extra', LL('cms::form.page_extra', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('page_extra', $page_extra, $page_extra_selected, array('class' => 'span2', 'id' => 'page_extra')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="page_owner">
									{{Form::label('page_owner', LL('cms::form.page_owner', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('page_owner', $page_owner, $page_owner_selected, array('class' => 'span2', 'id' => 'page_owner')) . "\n"}}
									</div>
								</div>
								<div class="control-group">
									{{Form::label('page_access', LL('cms::form.page_access', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('page_access', $page_access, $page_access_selected, array('class' => 'span2', 'id' => 'page_access')) . "\n"}}
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<label class="checkbox">
											{{Form::checkbox('is_home', 1, $page_is_home, array('id' => 'page_is_home'))}}
											{{LL('cms::form.page_is_home', CMSLANG)}}
										</label>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">

										<?php $url_preview = URL::base().$page_parent_slug.'/'.$page_slug.Config::get('cms::settings.preview'); ?>

										<a href="{{$url_preview}}" class="btn btn-mini span1 preview" target="_blank">{{LL('cms::button.page_preview', CMSLANG)}}</a>
										<label class="checkbox">
											{{Form::checkbox('is_valid', 1, $page_is_valid, array('id' => 'page_is_valid'))}}
											{{LL('cms::form.page_is_valid', CMSLANG)}}
										</label>
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_settings">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::page', array($page_lang))}}" class="btn btn-danger save_form" rel="form_settings">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::page', array($page_lang))}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
									@if(!empty($page_id))
									<a href="#page-clone-{{$page_id}}" class="btn btn-primary pull-right" data-toggle="modal">
										<i class="icon-repeat icon-white"></i>
										{{LL('cms::button.page_clone', CMSLANG)}}
									</a>
									@endif
								</div>
							</fieldset>
						{{Form::close()}}

					</div>

					<!-- DESIGN FORM -->
					<div class="tab-pane" id="design">

						{{Form::open(action('cms::ajax_page@save_design'), 'POST', array('class' => 'form-vertical', 'id' => 'form_design')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.page_legend_design', CMSLANG)}}</legend>

								<div class="row">

									<div class="span4">

										<div class="control-group">
											{{Form::label('page_template', LL('cms::form.page_template', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::select('page_template', $page_template, $page_template_selected, array('class' => 'span3', 'id' => 'page_template'))}}
											</div>
										</div>

										<div class="control-group">
											{{Form::label('page_header', LL('cms::form.page_header', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::select('page_header', $page_header, $page_header_selected, array('class' => 'span3', 'id' => 'page_header'))}}
											</div>
										</div>
										<div class="control-group">
											{{Form::label('page_layout', LL('cms::form.page_layout', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::select('page_layout', $page_layout, $page_layout_selected, array('class' => 'span3', 'id' => 'page_layout'))}}
											</div>
										</div>
										<div class="control-group">
											{{Form::label('page_footer', LL('cms::form.page_footer', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::select('page_footer', $page_footer, $page_footer_selected, array('class' => 'span3', 'id' => 'page_footer'))}}
											</div>
										</div>

									</div>

									<div class="span6" id="template-preview">

										<div class="row-fluid" id="header-preview">

											{{ Config::get('cms::theme.header.'.$page_header_selected) }}

										</div>

										<div class="row-fluid" id="layout-preview">

											{{ $page_layout_preview }}

										</div>

										<div class="row-fluid" id="footer-preview">

											{{ Config::get('cms::theme.footer.'.$page_footer_selected) }}

										</div>

									</div>

								</div>

								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_design">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::page')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</fieldset>
						{{Form::close()}}

					</div>

					<!-- SEO FORM -->
					<div class="tab-pane" id="seo">
						
						{{Form::open(action('cms::ajax_page@save_seo'), 'POST', array('class' => 'form-vertical', 'id' => 'form_seo')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.page_legend_seo', CMSLANG)}}</legend>
								<div class="control-group" rel="page_title">
									{{Form::label('page_title', LL('cms::form.page_title', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('page_title', $page_title, array('class' => 'span6 count70', 'id' => 'page_title'))}}
									</div>
								</div>
								<div class="control-group">
									{{Form::label('page_keyw', LL('cms::form.page_keyw', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::textarea('page_keyw', $page_keyw, array('class' => 'span6 elastic', 'id' => 'page_keyw', 'rows' => 2))}}
									</div>
								</div>
								<div class="control-group" rel="page_descr">
									{{Form::label('page_descr', LL('cms::form.page_descr', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::textarea('page_descr', $page_descr, array('class' => 'span6 count150 elastic', 'id' => 'page_descr', 'rows' => 2))}}
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_seo">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::page')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</fieldset>
						{{Form::close()}}
					</div>

					<!-- MEDIA FORM -->
					<div class="tab-pane" id="media">

						<legend>{{LL('cms::form.page_legend_media', CMSLANG)}}</legend>

						<div>
							<div class="well">
								<div>
									{{LL('cms::alert.upload_allowed', CMSLANG, array('format' => Config::get('cms::settings.mimes'), 'size' => Config::get('cms::settings.max_size')))}}
								</div>
								<div id="filelist">{{LL('cms::alert.upload_unavailable', CMSLANG)}}</div>
							</div>
						</div>

						<div id="media-container"></div>
						
						@if(!$role_fail)
						<div class="row space">
							<div class="span">
								<a href="#" class="btn btn-primary" id="add_media">
									<i class="icon-plus icon-white"></i>
									{{LL('cms::button.page_media_add', CMSLANG)}}
								</a>
								<a href="#" class="btn btn-danger" id="upload_media">
									<i class="icon-upload icon-white"></i>
									{{LL('cms::button.page_media_upload', CMSLANG)}}
								</a>
							</div>								
						</div>
						@endif

						<legend class="space">{{LL('cms::form.page_legend_media_available', CMSLANG)}}</legend>

						<ul class="thumbnails" id="media-box">

							@forelse ($files as $file)
							<li class="span1 media-box-block">
								@if (MEDIA_TYPE($file->ext) == 'img')
								<a href="{{BASE.$file->path}}" class="thumbnail fancy" rel="tooltip" data-original-title="{{$file->name}}">							
									<img src="{{BASE.$file->thumb}}" width="50" heigth="50" alt="">							
								</a>
								@else
								<a href="{{BASE.$file->path}}" class="thumbnail" rel="tooltip" data-original-title="{{$file->name}}">							
									<img src="{{BASE}}/bundles/cms/img/{{$file->ext}}_ico.png" width="100" heigth="100" alt="">							
								</a>
								@endif
							</li>
							@empty
							<li class="span3 none">{{LL('cms::alert.list_empty', CMSLANG)}}</li>
							@endforelse

						</ul>

					</div>

					<!-- PREVIEW FORM TAB -->
					<div class="tab-pane" id="preview">
						
						{{Form::open(action('cms::ajax_page@save_preview'), 'POST', array('class' => 'form-vertical', 'id' => 'form_preview')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.page_legend_abstract', CMSLANG)}}</legend>
								<div class="control-group">
									{{Form::label('page_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{HTML::span(CONF('cms::settings.langs', LANG), array('class' => 'label label-warning')) . "\n"}}
									</div>
								</div>
								<div class="control-group relative">
									{{Form::label('page_preview', LL('cms::form.page_preview', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls text-btn">
										<a href="#modal-sample" class="btn open-sample-modal" data-toggle="modal">
											<i class="icon-plus"></i>
											{{LL('cms::button.sample_pick', CMSLANG)}}
										</a>
										<a href="#" class="btn btn-primary open-media-modal" rel="{{$page_id}}">
											<i class="icon-plus icon-white"></i>
											{{LL('cms::button.media_pick', CMSLANG)}}
										</a>
										<a href="#modal-marker" class="btn btn-primary" data-toggle="modal">
											<i class="icon-plus icon-white"></i>
											{{LL('cms::button.marker_pick', CMSLANG)}}
										</a>
									</div>
									@if(EDITOR == 'ckeditor')
									<div class="controls">
										{{Form::textarea('page_preview', $page_preview, array('class' => 'span6 editorck', 'id' => 'preview_text', 'rows' => 8))}}
									</div>
									@endif
																		
									@if(EDITOR == 'markitup')
									<div class="controls">
										{{Form::textarea('page_preview', $page_preview, array('class' => 'html', 'id' => 'markitup', 'rows' => 8))}}
									</div>
									@endif
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_preview">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::page')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</fieldset>
						{{Form::close()}}

					</div>

					<!-- ORDER TAB -->
					<div class="tab-pane" id="order">
						
						<legend>{{LL('cms::form.page_legend_order_submenu', CMSLANG)}}</legend>

						<ul class="sortable">
						@if(!$role_fail)
							@forelse ($subpages as $page)
								<li id="{{$page->parent_id}}_{{$page->id}}">								
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
						@else
							<li>
								{{LL('cms::alert.list_empty', CMSLANG)}}
							</li>
						@endif
						</ul>

					</div>

					<!-- RELATIONS TAB -->
					<div class="tab-pane" id="relations">

						{{Form::open(action('cms::ajax_page@save_relations'), 'POST', array('class' => 'form-vertical', 'id' => 'form_relations')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.page_legend_page_rels', CMSLANG)}}</legend>

								<p><strong>{{$page_name}}</strong></p>

								<ul class="unstyled page-list hspace">

									@forelse($pagedata as $page)

										@if($page->id != $page_id)
										<li>
											
											<div class="control-group">
												<div class="controls">
													<label class="checkbox">
														<?php 
															if(!empty($pagerels)) {
																foreach($pagerels as $rel) {
																	$valid = ($rel->id == $page->id) ? true : false;
																	if($rel->id == $page->id) break;
																}
															} else {
																$valid = false;
															}
														?>
														{{Form::checkbox('rel_id[]', $page->id, $valid)}}
														@for ($i=0; $i < substr_count($page->slug, '/') - 1; $i++)
														<i class="icon-chevron-right"></i>
														@endfor
														{{$page->name}}
													</label>
												</div>
											</div>

										</li>
										@endif

									@empty
										<li>{{LL('cms::alert.list_empty', CMSLANG)}}</li>
									@endforelse
								</ul>

								@if(!empty($pagedata))

								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_relations">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::page')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>

								@endif

							</fieldset>
						{{Form::close()}}

					</div>

				</div>

			</div>
		</div>

	</div>
</div>

<div class="modal hide" id="modal-media">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>{{LL('cms::form.modal_title_media', CMSLANG)}}</h3>
	</div>
	<div class="modal-body">
		<table class="table fixed v-middle">
			<col width="12%">
			<col width="68%">
			<col width="20%">
			<tbody id="modal-media-list">
				
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
	</div>
</div>

<div class="modal hide" id="modal-marker">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>{{LL('cms::form.modal_title_marker', CMSLANG)}}</h3>
	</div>
	<div class="modal-body">
		{{View::make('cms::interface.partials.markers')}}
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
	</div>
</div>

<div class="modal hide" id="modal-sample">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>{{LL('cms::form.modal_title_sample', CMSLANG)}}</h3>
	</div>
	<div class="modal-body">
		{{View::make('cms::interface.partials.samples')}}
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
	</div>
</div>

@if(!empty($page_id))
<div class="modal hide" id="page-clone-{{$page_id}}">
	{{Form::open(action('cms::page@clone_page'), 'POST')}}
	{{Form::hidden('page_id', $page_id)}}
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>{{LL('cms::form.modal_title_clone_page', CMSLANG)}}</h3>
	</div>
	<div class="modal-body">
		@if(!$role_fail)
		<p>{{LL('cms::form.modal_descr_clone_page', CMSLANG)}}</p>
		<p>{{Form::select('lang', Config::get('cms::settings.langs'), LANG, array('class' => 'span6'))}}</p>
		<label class="checkbox">
			{{Form::checkbox('clone_media', 1, null)}}
			{{LL('cms::form.modal_page_clone_media', CMSLANG)}}
		</label>
		<label class="checkbox">
			{{Form::checkbox('checkall_clone', null, null, array('id' => 'checkall_clone'))}}
			{{LL('cms::form.modal_page_clone_elements', CMSLANG)}}
		</label>		

			@foreach($elements as $element)
			<div>
				<label class="checkbox inline">
					<span class="label label-info">{{$element->zone}}</span>
					{{Form::checkbox('clone_elements[]', $element->id, null, array('class' => 'to_clone'))}}
					{{$element->label}}
				</label>
				<label class="checkbox inline pull-right">
					{{LL('cms::form.element_separate', CMSLANG)}}
					{{Form::checkbox('ele_separate[]', $element->id, null)}}
				</label>
			</div>
			@endforeach

		@else
		<p>{{LL('cms::ajax_resp.ownership_error', CMSLANG)}}</p>
		@endif
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">{{LL('cms::button.close', CMSLANG)}}</a>
		@if(!$role_fail)
		{{Form::submit(LL('cms::button.clone', CMSLANG), array('class' => 'btn btn-danger'))}}
		@endif
	</div>
	{{Form::close()}}
</div>
@endif
