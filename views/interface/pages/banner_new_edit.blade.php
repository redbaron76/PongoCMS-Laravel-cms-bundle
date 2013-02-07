<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::banner')}}" class="btn btn-inverse pull-right">
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
					<li class="active"><a href="#banner" data-toggle="tab">{{LL('cms::form.banner', CMSLANG)}}</a></li>
					<li{{DISABLED($banner_id)}}><a href="#order" data-toggle="tab">{{LL('cms::form.banner_order', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- banner FORM TAB -->
					<div class="tab-pane active" id="banner">
						{{Form::open(action('cms::ajax_banner@save_banner'), 'POST', array('class' => 'form-vertical', 'id' => 'form_banner')) . "\n"}}
						{{Form::hidden('banner_id', $banner_id, array('class' => 'banner_id', 'id' => 'banner_id')) . "\n"}}
						<legend>{{LL('cms::form.banner_legend', CMSLANG)}}</legend>

						<div class="row">
							
							<div class="span4 nobottom">
								
								<fieldset>
									<div class="control-group">
										{{Form::label('banner_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
										<div class="controls">
											{{Form::hidden('banner_lang', $banner_lang, array('id' => 'banner_lang')) . "\n"}}
											{{HTML::span(CONF('cms::settings.langs', $banner_lang), array('class' => 'label label-warning')) . "\n"}}
										</div>
									</div>
									<div class="control-group" rel="banner_name">
										{{Form::label('banner_name', LL('cms::form.banner_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
										<div class="controls">
											{{Form::text('banner_name', $banner_name, array('class' => 'span3', 'id' => 'banner_name')) . "\n"}}
										</div>
									</div>								
								</fieldset>

							</div>
						</div>

						<div class="row">
							<div class="span10">
								<h4>{{LL('cms::title.banner_files_active', CMSLANG)}}</h4>
								<div class="trans-box hspace">
									<table class="table table-striped fixed v-middle">
										<col width="10%">
										<col width="80%">
										<tbody>
											@forelse($files_select as $file)
											<tr>
												<td>
													<a href="{{BASE.$file->path}}" class="thumbnail fancy" data-original-title="{{$file->name}}">							
														<img src="{{BASE.$file->thumb}}">
													</a>
												</td>
												<td class="v-middle">
													{{HTML::link_to_action('cms::file@edit', LL('cms::button.delete', CMSLANG), array($file->id), array('class' => 'edit_banner btn btn-mini'))}}
													{{$file->name}}
													<div class="hspace">
														{{Form::text('url['.$file->id.']', $file->pivot->url, array('class' => 'span7', 'placeholder' => LL('cms::form.banner_url', CMSLANG)))}}
														{{Form::text('date_off['.$file->id.']', db2Date($file->pivot->date_off, false), array('class' => 'span2 date_off', 'placeholder' => LL('cms::form.banner_dateoff', CMSLANG)))}}					
														<label class="checkbox inline">
															{{Form::checkbox('is_blank['.$file->id.']', 1, $file->pivot->is_blank)}}
															{{LL('cms::form.banner_target', CMSLANG)}}
														</label>
														<label class="checkbox inline">
															{{Form::checkbox('wm['.$file->id.']', 1, $file->pivot->wm)}}
															{{LL('cms::form.banner_watermark', CMSLANG)}}
														</label>
														{{Form::hidden('file_id['.$file->id.']', $file->id)}}														
													</div>
												</td>
											</tr>
											@empty
											<tr>
												<td colspan="3" class="toleft">
													{{LL('cms::alert.list_empty', CMSLANG)}}
												</td>
											</tr>
											@endforelse
										</tbody>

									</table>
									
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="span10">
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_banner">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::banner', array($banner_lang))}}" class="btn btn-danger save_form" rel="form_banner">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::banner')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</div>
						</div>
						{{Form::close()}}
					</div>

					<div class="tab-pane" id="order">

						<legend>{{LL('cms::form.banner_legend_order', CMSLANG)}}</legend>

						<ul class="thumbnails sortable">

						@forelse ($files_select as $file)
							<li class="span1" id="{{$banner_id}}_{{$file->id}}">
								<a href="#" class="thumbnail" data-original-title="{{$file->name}}">
									<img src="{{BASE.$file->thumb}}" width="50" heigth="50" alt="">							
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
