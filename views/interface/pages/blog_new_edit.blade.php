<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::blog')}}" class="btn btn-inverse pull-right">
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
					<li class="active"><a href="#post" data-toggle="tab">{{LL('cms::button.blog_post', CMSLANG)}}</a></li>
					<li{{DISABLED($blog_id)}}><a href="#preview" data-toggle="tab">{{LL('cms::button.page_abstract', CMSLANG)}}</a></li>
					<li{{DISABLED($blog_id)}}><a href="#seo" data-toggle="tab">{{LL('cms::button.blog_seo', CMSLANG)}}</a></li>
					<li{{DISABLED($blog_id)}}><a href="#tags" data-toggle="tab">{{LL('cms::button.blog_tags', CMSLANG)}}</a></li>
					<li{{DISABLED($blog_id)}}><a href="#media" data-toggle="tab">{{LL('cms::button.blog_media', CMSLANG)}}</a></li>
					<li{{DISABLED($blog_id)}}><a href="#available" data-toggle="tab">{{LL('cms::form.available', CMSLANG)}}</a></li>
					<li{{DISABLED($blog_id)}}><a href="#relations" data-toggle="tab">{{LL('cms::form.blog_relation', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">
					
					<!-- POST FORM -->
					<div class="tab-pane active" id="post">
						{{Form::open(action('cms::ajax_blog@save_post'), 'POST', array('class' => 'form-vertical', 'id' => 'form_settings')) . "\n"}}
							{{Form::hidden('blog_id', $blog_id, array('class' => 'blog_id')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.blog_legend_post', CMSLANG)}}</legend>
								<div class="control-group">
									{{Form::label('blog_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::hidden('blog_lang', $blog_lang, array('id' => 'blog_lang')) . "\n"}}
										{{HTML::span(CONF('cms::settings.langs', $blog_lang), array('class' => 'label label-warning')) . "\n"}}
									</div>
								</div>
								<div class="row">
									<div class="span5">
										<div class="control-group" rel="blog_date_on">
											{{Form::label('blog_date_on', LL('cms::form.blog_date_on', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::text('blog_date_on', $blog_date_on, array('class' => 'span2 datetimepicker_on', 'id' => 'blog_date_on')) . "\n"}}
											</div>
										</div>
									</div>
									<div class="span5">
										<div class="control-group" rel="blog_date_off">
											{{Form::label('blog_date_off', LL('cms::form.blog_date_off', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::text('blog_date_off', $blog_date_off, array('class' => 'span2 datetimepicker_off', 'id' => 'blog_date_off')) . "\n"}}
											</div>
										</div>
									</div>
								</div>								
								
								<div class="control-group" rel="blog_name">
									{{Form::label('blog_name', LL('cms::form.blog_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('blog_name', $blog_name, array('class' => 'span7', 'id' => 'blog_name')) . "\n"}}
									</div>
								</div>								
								<br>
								<div class="control-group relative">
									{{Form::label('blog_text', LL('cms::form.blog_text', CMSLANG), array('class' => 'control-label')) . "\n"}}
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
										{{Form::textarea('blog_text', $blog_text, array('class' => 'span6 editorck', 'id' => 'blog_text', 'rows' => 8))}}
									</div>
									@endif
									
									@if(EDITOR == 'markitup')
									<div class="controls">
										{{Form::textarea('blog_text', $blog_text, array('class' => 'html', 'id' => 'markitup', 'rows' => 8))}}
									</div>
									@endif
								</div>
								<br>
								<div class="control-group" rel="blog_parent">
									{{Form::label('blog_parent', LL('cms::form.blog_parent', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('blog_parent', $blog_parent, $blog_parent_selected, array('id' => 'blog_parent', 'class' => 'span7')) . "\n"}}
									</div>
								</div>
								<div class="control-group" rel="blog_slug">
									{{Form::label('blog_slug', LL('cms::form.blog_slug', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										<div class="input-prepend">
											<span class="add-on" rel="blog_slug">{{$blog_parent_slug}}</span>
											<?php $span = (strlen($blog_parent_slug) > 5) ? 'span5' : 'span7';   ?>
											{{Form::text('blog_slug', $blog_slug, array('class' => $span, 'id' => 'blog_slug')) . "\n"}}
											{{Form::hidden('blog_parent_slug', $blog_parent_slug, array('id' => 'blog_parent_slug')) . "\n"}}
										</div>
									</div>
								</div>
								<div class="control-group" rel="blog_zone">
									{{Form::label('blog_zone', LL('cms::form.element_zone', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::select('blog_zone', $blog_zones, $blog_zone_selected, array('id' => 'blog_zone')) . "\n"}}
									</div>
								</div>
								<div class="control-group">
									<div class="controls">

										<?php $url_preview = URL::base().$blog_parent_slug.$blog_slug.Config::get('cms::settings.preview'); ?>

										<a href="{{$url_preview}}" class="btn btn-mini span1 preview" target="_blank">{{LL('cms::button.page_preview', CMSLANG)}}</a>
										<label class="checkbox">
											{{Form::checkbox('is_valid', 1, $blog_is_valid, array('id' => 'blog_is_valid'))}}
											{{LL('cms::form.blog_is_valid', CMSLANG)}}
										</label>
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_settings">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog', array($blog_lang))}}" class="btn btn-danger save_form" rel="form_settings">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</fieldset>
						{{Form::close()}}

					</div>

					<!-- PREVIEW FORM TAB -->
					<div class="tab-pane" id="preview">
						
						{{Form::open(action('cms::ajax_blog@save_preview'), 'POST', array('class' => 'form-vertical', 'id' => 'form_preview')) . "\n"}}
							{{Form::hidden('blog_id', $blog_id, array('class' => 'blog_id')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.blog_legend_abstract', CMSLANG)}}</legend>
								<div class="control-group">
									{{Form::label('blog_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{HTML::span(CONF('cms::settings.langs', $blog_lang), array('class' => 'label label-warning')) . "\n"}}
									</div>
								</div>
								<div class="control-group relative">
									{{Form::label('blog_preview', LL('cms::form.blog_preview', CMSLANG), array('class' => 'control-label')) . "\n"}}
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
										{{Form::textarea('blog_preview', $blog_preview, array('class' => 'span6 editorck', 'id' => 'blog_preview', 'rows' => 8))}}
									</div>
									@endif
																		
									@if(EDITOR == 'markitup')
									<div class="controls">
										{{Form::textarea('blog_preview', $blog_preview, array('class' => 'html', 'id' => 'markitup', 'rows' => 8))}}
									</div>
									@endif
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_preview">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</fieldset>
						{{Form::close()}}
					</div>

					<!-- SEO FORM -->
					<div class="tab-pane" id="seo">
						
						{{Form::open(action('cms::ajax_blog@save_seo'), 'POST', array('class' => 'form-vertical', 'id' => 'form_seo')) . "\n"}}
							{{Form::hidden('blog_id', $blog_id, array('class' => 'blog_id')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								<legend>{{LL('cms::form.blog_legend_seo', CMSLANG)}}</legend>
								<div class="control-group" rel="blog_title">
									{{Form::label('blog_title', LL('cms::form.blog_title', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('blog_title', $blog_title, array('class' => 'span6 count70', 'id' => 'blog_title'))}}
									</div>
								</div>
								<div class="control-group">
									{{Form::label('blog_keyw', LL('cms::form.page_keyw', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::textarea('blog_keyw', $blog_keyw, array('class' => 'span6 elastic', 'id' => 'blog_keyw', 'rows' => 2))}}
									</div>
								</div>
								<div class="control-group" rel="blog_descr">
									{{Form::label('blog_descr', LL('cms::form.blog_descr', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::textarea('blog_descr', $blog_descr, array('class' => 'span6 count150 elastic', 'id' => 'blog_descr', 'rows' => 2))}}
									</div>
								</div>
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_seo">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</fieldset>
						{{Form::close()}}
					</div>

					<div class="tab-pane" id="tags">
						
						{{Form::open(action('cms::ajax_blog@save_tags'), 'POST', array('class' => 'form-vertical form-inline', 'id' => 'form_tags')) . "\n"}}
							{{Form::hidden('blog_id', $blog_id, array('class' => 'blog_id')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>
								
								<legend>{{LL('cms::form.blog_legend_tags', CMSLANG)}}</legend>

								<div class="control-group" rel="blog_title">
									{{Form::label('blog_tags', LL('cms::form.blog_tags', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('blog_tags', '', array('class' => 'span6', 'id' => 'tags_text'))}}
									</div>
								</div>

								<div class="control-group space" rel="tag_name">
									{{Form::label('tag_name', LL('cms::form.new_tag', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										<div class="input-append">
											{{Form::text('tag_name', '', array('class' => 'span3 append', 'id' => 'new_tag'))}}
											<button class="btn" type="button" id="add_tag">
												<i class="icon-plus"></i>
												{{LL('cms::button.add_tag', CMSLANG)}}
											</button>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_tags">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog')}}" class="btn">
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

						<div>
							<div>

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
						</div>

					</div>

					<!-- RELATIONS TAB -->
					<div class="tab-pane" id="available">

						{{Form::open(action('cms::ajax_blog@save_available'), 'POST', array('class' => 'form-vertical', 'id' => 'form_available')) . "\n"}}
							{{Form::hidden('blog_id', $blog_id, array('class' => 'blog_id')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							{{Form::hidden('rel_id[]', $page_id) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.blog_legend_blog_available', CMSLANG)}}</legend>

								<p><strong>{{$blog_name}}</strong></p>

								<ul class="unstyled page-list hspace">
									
									@if(!empty($pagedata))

										@forelse($pagedata as $page)
											
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
															@if($page->id != $page_id)
																@if($page->extra_id != setExtra('blogs'))
																	{{Form::checkbox('rel_id[]', '', false, array('disabled' => 'disabled'))}}
																@else
																	{{Form::checkbox('rel_id[]', $page->id, $valid)}}
																@endif
															@else
																{{Form::checkbox('rel_id[]', $page->id, true, array('disabled' => 'disabled'))}}
															@endif

															@for ($i=0; $i < substr_count($page->slug, '/') - 1; $i++)
															<i class="icon-chevron-right"></i>
															@endfor

															{{$page->name}}
														</label>
													</div>
												</div>

											</li>

										@empty
											<li>{{LL('cms::alert.list_empty', CMSLANG)}}</li>
										@endforelse

									@else
										<li>{{LL('cms::alert.list_empty', CMSLANG)}}</li>
									@endif

								</ul>

								@if(!empty($pagedata))
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_available">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
								@endif

							</fieldset>
						{{Form::close()}}

					</div>

					<!-- RELATIONS TAB -->
					<div class="tab-pane" id="relations">

						{{Form::open(action('cms::ajax_blog@save_relations'), 'POST', array('class' => 'form-vertical', 'id' => 'form_relations')) . "\n"}}
							{{Form::hidden('blog_id', $blog_id, array('class' => 'blog_id')) . "\n"}}
							{{Form::hidden('page_id', $page_id, array('class' => 'page_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.blog_legend_blog_rels', CMSLANG)}}</legend>

								<p><strong>{{$blog_name}}</strong></p>

								<div class="hspace">

									<div class="loading">

										<ul class="unstyled page-list listing">
											
											@if(!empty($blogdata->results))

												@forelse($blogdata->results as $blog)

													@if($blog->id != $blog_id)
													<li>
														
														<div class="control-group">
															<div class="controls">
																<label class="checkbox">
																	<?php 
																		if(!empty($blogrels)) {
																			foreach($blogrels as $rel) {
																				$valid = ($rel->id == $blog->id) ? true : false;
																				if($rel->id == $blog->id) break;
																			}
																		} else {
																			$valid = false;
																		}
																	?>
																	{{Form::checkbox('rel_id[]', $blog->id, $valid)}}
																	@for ($i=0; $i < substr_count($blog->slug, '/') - 1; $i++)
																	<i class="icon-chevron-right"></i>
																	@endfor

																	<span class="pop-over" rel="{{$blog->id}}" data-original-title="{{LL('cms::title.popover_title_blog', CMSLANG)}}">
																		{{$blog->name}}
																	</span>

																</label>
															</div>
														</div>

													</li>
													@endif

												@empty
													<li>{{LL('cms::alert.list_empty', CMSLANG)}}</li>
												@endforelse

												@if($blogdata->total > Config::get('cms::theme.pag') and $blogdata->page < $blogdata->last)
												{{$blogdata->next()}}
												@endif

											@else
												<li>{{LL('cms::alert.list_empty', CMSLANG)}}</li>
											@endif

										</ul>

									</div>
									
								</div>

								@if(!empty($blogdata->results))
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_relations">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::blog')}}" class="btn">
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
