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
					<li><a href="#order" data-toggle="tab">{{LL('cms::form.banner_order', CMSLANG)}}</a></li>
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
								<h4>{{LL('cms::title.banner_files', CMSLANG)}}</h4>
								<div class="trans-box hspace loading">
									<table class="table table-striped fixed v-middle listing">
										<col width="10%">
										<col width="82%">
										<col width="8%">
										<tbody>
											@forelse($files->results as $file)
											<tr>

												<?php 
													if(!empty($files_select)) {	

														foreach($files_select as $image) {
															$valid = ($image->pivot->cmsfile_id == $file->id) ? true : false;
															$url = ($valid) ? $image->pivot->url : '';
															$date_off = ($valid) ? db2Date($image->pivot->date_off) : '';
															$is_blank = ($valid) ? ((bool) $image->pivot->is_blank) : false;
															if($image->pivot->cmsfile_id == $file->id) break;
														}
													} else {
														$valid = false;
														$url = '';
														$date_off = '';
														$is_blank = false;
													}
												?>

												<td>
													<a href="{{BASE.$file->path}}" class="thumbnail fancy" rel="tooltip" data-original-title="{{$file->name}}">							
														<img src="{{BASE.$file->thumb}}" width="50" heigth="50" alt="">							
													</a>
												</td>
												<td class="v-middle">
													{{$file->name}}
													<div class="hspace">
														{{Form::text('url['.$file->id.']', $url, array('class' => 'span6', 'placeholder' => LL('cms::form.banner_url', CMSLANG)))}}
														{{Form::text('date_off['.$file->id.']', $date_off, array('class' => 'span2 date_off', 'placeholder' => LL('cms::form.banner_dateoff', CMSLANG)))}}
														<label class="checkbox">
															{{Form::checkbox('is_blank['.$file->id.']', 1, $is_blank)}}
															{{LL('cms::form.banner_target', CMSLANG)}}
														</label>
													</div>
												</td>
												<td>													
													{{Form::checkbox('file_id['.$file->id.']', $file->id, $valid)}}
												</td>
											</tr>
											@empty
											<tr>
												<td colspan="3">
													{{LL('cms::alert.list_empty', CMSLANG)}}
												</td>
											</tr>
											@endforelse

											<tr class="navigation">
												<td colspan="3" class="row space">
													<div class="toright">
														{{$files->next()}}
													</div>
												</td>
											</tr>

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
								<a href="{{BASE.$file->path}}" class="thumbnail fancy" data-original-title="{{$file->name}}" rel="tooltip">
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







