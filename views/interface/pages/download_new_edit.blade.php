<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::download')}}" class="btn btn-inverse pull-right">
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
					<li class="active"><a href="#download" data-toggle="tab">{{LL('cms::form.download', CMSLANG)}}</a></li>
					<li><a href="#order" data-toggle="tab">{{LL('cms::form.download_order', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- download FORM TAB -->
					<div class="tab-pane active" id="download">

						<legend>{{LL('cms::form.download_legend', CMSLANG)}}</legend>

						<div class="row">
							{{Form::open(action('cms::ajax_download@save_download'), 'POST', array('class' => 'form-vertical', 'id' => 'form_download')) . "\n"}}
							{{Form::hidden('download_id', $download_id, array('class' => 'download_id', 'id' => 'download_id')) . "\n"}}
							<div class="span4 nobottom">
								
									<fieldset>

										<div class="control-group" rel="download_name">
											{{Form::label('download_name', LL('cms::form.download_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
											<div class="controls">
												{{Form::text('download_name', $download_name, array('class' => 'span3', 'id' => 'download_name')) . "\n"}}
											</div>
										</div>									

									</fieldset>

							</div>
							<div class="span6">
								<h4>{{LL('cms::title.download_files', CMSLANG)}}</h4>
								<div class="trans-box hspace loading">
									<table class="table table-striped fixed v-middle listing">
										<col width="15%">
										<col width="75%">
										<col width="10%">
										<tbody>
											@forelse($files->results as $file)
											<tr>
												<td>
													<a href="{{BASE.$file->path}}" class="thumbnail fancy" rel="tooltip" data-original-title="{{$file->name}}">							
														<img src="{{BASE.$file->thumb}}" width="50" heigth="50" alt="">							
													</a>
												</td>
												<td class="v-middle">{{$file->name}}</td>
												<td>
													<?php 
														if(!empty($files_select)) {															
															foreach($files_select as $image) {
																$valid = ($image->pivot->cmsfile_id == $file->id) ? true : false;
																if($image->pivot->cmsfile_id == $file->id) break;
															}
														} else {
															$valid = false;
														}															
													?>
													{{Form::checkbox('file_id[]', $file->id, $valid)}}
												</td>
											</tr>
											@empty
											<tr>
												<td colspan="3">
													{{LL('cms::alert.list_empty', CMSLANG)}}
												</td>
											</tr>
											@endforelse

										</tbody>

									</table>

									@if($files->total > Config::get('cms::theme.pag'))
									<div class="navigation">
										<ul class="unstyled toright">
											{{$files->next()}}
										</ul>
									</div>
									@endif

								</div>
							</div>
							{{Form::close()}}
						</div>
						
						<div class="row">
							<div class="span10">
								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_download">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::download')}}" class="btn btn-danger save_form" rel="form_download">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::download')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>
							</div>
						</div>

					</div>

					<div class="tab-pane" id="order">

						<legend>{{LL('cms::form.download_legend_order', CMSLANG)}}</legend>

						<ul class="thumbnails sortable">

						@forelse ($files_select as $file)
							<li class="span1" id="{{$download_id}}_{{$file->id}}">
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







