<div class="row">
	<div class="span12">
		<h2>{{LL('cms::title.translations', CMSLANG)}}</h2>
	</div>
</div>

<div class="row space">
	<div class="span4 nobottom">
		{{Form::open(action('cms::ajax_translation@save_translation'), 'POST', array('class' => 'form-vertical', 'id' => 'form_translation')) . "\n"}}
		{{Form::hidden('translation_id', '', array('class' => 'translation_id', 'id' => 'translation_id')) . "\n"}}
		{{Form::hidden('lang_from', $lang_from) . "\n"}}
		{{Form::hidden('lang_to', $lang_to) . "\n"}}
			<fieldset>

				<div class="control-group">
					{{Form::label('trans_to', LL('cms::form.trans_to', CMSLANG), array('class' => 'control-label')) . "\n"}}
					<div class="controls">
						{{Form::select('trans_to', $langs, $lang_to)}}
					</div>
				</div>

				<div class="control-group" rel="word">
					{{Form::label('word', LL('cms::form.trans_word', CMSLANG), array('class' => 'control-label')) . "\n"}}
					<div class="controls">
						{{Form::textarea('word', '', array('class' => 'span4', 'id' => 'word', 'rows' => 5)) . "\n"}}
					</div>
				</div>

				<div class="control-group" rel="value">
					{{Form::label('value', LL('cms::form.trans_value', CMSLANG), array('class' => 'control-label')) . "\n"}}
					<div class="controls">
						{{Form::textarea('value', '', array('class' => 'span4', 'id' => 'value', 'rows' => 5)) . "\n"}}
					</div>
				</div>

				<div class="form-actions">
					<a href="#" class="btn btn-success save_form pull-right" rel="form_translation">
						<i class="icon-ok icon-white"></i>
						{{LL('cms::button.save', CMSLANG)}}
					</a>
				</div>

			</fieldset>
		{{Form::close()}}
	</div>
	<div class="span1">
		&nbsp;
	</div>
	<div class="span7">
		<h4>{{LL('cms::title.current_translation', CMSLANG)}}</h4>
		<div class="trans-box">
			<table class="table table-striped fixed hspace v-middle" id="translation">
				<col width="71%">
				<col width="29%">
				@forelse($data as $trans)
				<tr rel="{{$trans->id}}">
					<td>
						<div class="word" rel="{{$trans->id}}"><strong>{{$trans->word}}</strong></div>
						<div class="value" rel="{{$trans->id}}">{{$trans->value}}</div>
					</td>
					<td>
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="" class="btn btn-mini edit" rel="{{$trans->id}}">{{LL('cms::button.edit', CMSLANG)}}</a>
							</div>
							<div class="btn-group">
								<a href="" class="btn btn-mini btn-danger delete" rel="{{$trans->id}}">{{LL('cms::button.delete', CMSLANG)}}</a>
							</div>
						</div>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="2" class="toleft">
						{{LL('cms::alert.list_empty', CMSLANG)}}
					</td>
				</tr>
				@endforelse
			</table>
		</div>
	</div>
</div>