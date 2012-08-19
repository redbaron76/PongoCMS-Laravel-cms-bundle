<div class="row">
	<div class="span10">
		<h2>{{$title}}</h2>
	</div>
	<div class="span2">
		<a href="{{action('cms::tag')}}" class="btn btn-inverse pull-right">
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
					<li class="active"><a href="#tag" data-toggle="tab">{{LL('cms::form.tag', CMSLANG)}}</a></li>
				</ul>

			</div>
			<div class="span10 body">
				
				<div class="tab-content">

					<!-- tag FORM TAB -->
					<div class="tab-pane active" id="tag">
						{{Form::open(action('cms::ajax_tag@save_tag'), 'POST', array('class' => 'form-vertical', 'id' => 'form_tag')) . "\n"}}
						{{Form::hidden('tag_id', $tag_id, array('class' => 'tag_id')) . "\n"}}
							<fieldset>

								<legend>{{LL('cms::form.tag_legend', CMSLANG)}}</legend>

								<div class="control-group">
										{{Form::label('tag_lang', LL('cms::form.page_lang', CMSLANG), array('class' => 'control-label')) . "\n"}}
										<div class="controls">
											{{Form::hidden('tag_lang', $tag_lang, array('id' => 'tag_lang')) . "\n"}}
											{{HTML::span(CONF('cms::settings.langs', $tag_lang), array('class' => 'label label-warning')) . "\n"}}
										</div>
									</div>
								<div class="control-group" rel="tag_name">
									{{Form::label('tag_name', LL('cms::form.tag_name', CMSLANG), array('class' => 'control-label')) . "\n"}}
									<div class="controls">
										{{Form::text('tag_name', $tag_name, array('class' => 'span4', 'id' => 'tag_name')) . "\n"}}
									</div>
								</div>

								<div class="form-actions">
									<a href="#" class="btn btn-success save_form" rel="form_tag">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_continue', CMSLANG)}}
									</a>
									<a href="{{action('cms::tag', array($tag_lang))}}" class="btn btn-danger save_form" rel="form_tag">
										<i class="icon-ok icon-white"></i>
										{{LL('cms::button.save_exit', CMSLANG)}}
									</a>
									<a href="{{action('cms::tag')}}" class="btn">
										<i class="icon-remove"></i>
										{{LL('cms::button.page_exit', CMSLANG)}}
									</a>
								</div>

							</fieldset>
						{{Form::close()}}
					</div>

				</div>

			</div>
		</div>

	</div>
</div>