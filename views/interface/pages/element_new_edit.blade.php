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
					<li class="active"><a href="#element" data-toggle="tab">{{LL('cms::form.element_settings', CMSLANG)}}</a></li>
					@if(EDITOR == 'ckeditor')
					<li{{DISABLED($element_id)}}><a href="#ckeditor" data-toggle="tab">{{LL('cms::form.element_content', CMSLANG)}}</a></li>
					@endif
					@if(EDITOR == 'markitup')
					<li{{DISABLED($element_id)}}><a href="#markit" data-toggle="tab">{{LL('cms::form.element_content', CMSLANG)}}</a></li>
					@endif
					<li{{DISABLED($element_id)}}><a href="#media" data-toggle="tab">{{LL('cms::button.page_media', CMSLANG)}}</a></li>
					<li{{DISABLED($element_id)}}><a href="#order" data-toggle="tab">{{LL('cms::form.element_stack', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- ELEMENT FORM TAB -->
					<div class="tab-pane active" id="element">
						
						{{Form::open(action('cms::ajax_page@save_element_settings'), 'POST', array('class' => 'form-vertical', 'id' => 'form_element_settings')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							{{Form::hidden('element_id', $element_id, array('class' => 'element_id')) . "\n"}}
							<fieldset>
								<legend>{{$page_name}} / {{LL('cms::form.element_settings_legend', CMSLANG)}}</legend>

								<div class="row">

									<div class="span4">

										<div class="control-group">
											{{Form::label('page_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{HTML::span(CONF('cms::settings.langs', LANG), array('class' => 'label label-warning')) . "\n"}}
											</div>
										</div>								
										<div class="control-group" rel="element_label">
											{{Form::label('element_label', LL('cms::form.element_label', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::text('element_label', $element_label, array('class' => 'span3', 'id' => 'element_label'))}}
											</div>
										</div>
										<div class="control-group" rel="element_name">
											{{Form::label('element_name', LL('cms::form.element_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												<div class="input-prepend">
													<span class="add-on">#</span>
													{{Form::text('element_name', $element_name, array('class' => 'span2', 'id' => 'element_name'))}}
												</div>
											</div>
										</div>
										<div class="control-group" rel="element_zone">
											{{Form::label('element_zone', LL('cms::form.element_zone', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">										
												{{Form::select('element_zone', $element_zones, $element_zone_selected, array('id' => 'element_zone', 'class' => 'span3')) . "\n"}}
											</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<label class="checkbox">
													{{Form::checkbox('is_valid', 1, $element_is_valid, array('id' => 'element_is_valid'))}}
													{{LL('cms::form.element_is_valid', CMSLANG)}}
												</label>
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
									<a href="#" class="btn btn-success save_form" rel="form_element_settings">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::page', array(LANG))}}" class="btn btn-danger save_form" rel="form_element_settings">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::page', array(LANG))}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
									@if(!empty($element_id))
									<a href="#element-clone-{{$element_id}}" class="btn btn-primary pull-right" data-toggle="modal">
										<i class="icon-repeat icon-white"></i>
										{{LL('cms::button.element_clone', CMSLANG)}}
									</a>
									@endif
								</div>
							</fieldset>
						{{Form::close()}}
					</div>

					<!-- CKEDITOR FORM TAB -->
					@if(EDITOR == 'ckeditor')
					<div class="tab-pane" id="ckeditor">
						
						{{Form::open(action('cms::ajax_page@save_element_text'), 'POST', array('class' => 'form-vertical', 'id' => 'form_ckeditor')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							{{Form::hidden('element_id', $element_id, array('class' => 'element_id')) . "\n"}}
							<fieldset>
								<legend>{{$page_name}} / {{$element_label}} / {{LL('cms::form.element_ckeditor_legend', CMSLANG)}}</legend>
								<div class="control-group">
									{{Form::label('page_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{HTML::span(CONF('cms::settings.langs', LANG), array('class' => 'label label-warning')) . "\n"}}
									</div>
								</div>
								<div class="control-group relative">
									{{Form::label('element_text', LL('cms::form.element_text', CMSLANG), array('class' => 'control-label')) . "\n"}}
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
									<div class="controls">
										{{Form::textarea('element_text', $element_text, array('class' => 'span6 editorck', 'id' => 'element_text', 'rows' => 8))}}
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_ckeditor">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									@if(!empty($element_id))
									<a href="#element-clone-{{$element_id}}" class="btn btn-primary pull-right" data-toggle="modal">
										<i class="icon-repeat icon-white"></i>
										{{LL('cms::button.element_clone', CMSLANG)}}
									</a>
									@endif
								</div>
							</fieldset>
						{{Form::close()}}
					</div>
					@endif

					<!-- MARKITUP FORM TAB -->
					@if(EDITOR == 'markitup')
					<div class="tab-pane" id="markit">
						
						{{Form::open(action('cms::ajax_page@save_element_text'), 'POST', array('class' => 'form-vertical', 'id' => 'form_markitup')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							{{Form::hidden('element_id', $element_id, array('class' => 'element_id')) . "\n"}}
							<fieldset>
								<legend>{{$page_name}} / {{$element_label}} / {{LL('cms::form.element_markitup_legend', CMSLANG)}}</legend>
								<div class="control-group">
									{{Form::label('page_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{HTML::span(CONF('cms::settings.langs', LANG), array('class' => 'label label-warning')) . "\n"}}
									</div>
								</div>
								<div class="control-group relative">
									{{Form::label('element_text', LL('cms::form.element_text', CMSLANG), array('class' => 'control-label')) . "\n"}}
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
									<div class="controls">
										{{Form::textarea('element_text', $element_text, array('class' => 'html', 'id' => 'markitup', 'rows' => 8))}}
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_markitup">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									@if(!empty($element_id))
									<a href="#element-clone-{{$element_id}}" class="btn btn-primary pull-right" data-toggle="modal">
										<i class="icon-repeat icon-white"></i>
										{{LL('cms::button.element_clone', CMSLANG)}}
									</a>
									@endif
								</div>
							</fieldset>
						{{Form::close()}}
					</div>
					@endif

					<!-- MEDIA FORM -->
					<div class="tab-pane" id="media">

						<legend>{{$page_name}} / {{LL('cms::form.page_legend_media', CMSLANG)}}</legend>

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

						<div>
							<div>

								<ul class="thumbnails" id="media-box">

									@forelse ($media as $file)
									<li class="span1 media-box-block">
										<a href="{{BASE.$file->path}}" class="thumbnail fancy" rel="tooltip" data-original-title="{{$file->name}}">
											@if (MEDIA_TYPE($file->ext) == 'img')
											<img src="{{BASE.$file->thumb}}" width="100" heigth="100" alt="">
											@else
											<img src="{{BASE}}/bundles/cms/img/{{$file->ext}}_ico.png" width="100" heigth="100" alt="">
											@endif
										</a>
									</li>
									@empty
									<li class="span3 none">{{LL('cms::alert.list_empty', CMSLANG)}}</li>
									@endforelse

								</ul>

							</div>
						</div>

					</div>

					<!-- ORDER TAB -->
					<div class="tab-pane" id="order">
						
						<legend>{{$page_name}} / {{LL('cms::form.element_legend_stack', CMSLANG)}}</legend>

						<ul class="sortable">
						@if(!$role_fail)
							@forelse ($elements as $element)
								@if($element->zone == $element_zone_selected)
									<li id="{{$page_id}}_{{$element->id}}" data-zone="{{$element->zone}}">
										<a href="#" class="btn">
											<i class="icon-resize-vertical"></i>
											{{$element->label}}
										</a>
									</li>
								@endif
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

<div class="modal hide" id="element-clone-{{$element_id}}">
	{{Form::open(action('cms::page@clone_element'), 'POST')}}
	{{Form::hidden('element_id', $element_id)}}
	{{Form::hidden('page_id', $page_id)}}
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>{{LL('cms::form.modal_title_clone_element', CMSLANG)}}</h3>
	</div>
	<div class="modal-body">
		@if(!$role_fail)
		<p>{{LL('cms::form.modal_descr_clone_element', CMSLANG)}}</p>
		<p>{{Form::select('newpage_id', CmsPage::select_top_slug(LANG, $page_id, false), null, array('class' => 'span6'))}}</p>
		<label class="checkbox">
			{{Form::checkbox('to_clone', 1, null)}}
			{{LL('cms::form.element_to_clone', CMSLANG)}}
		</label>
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
