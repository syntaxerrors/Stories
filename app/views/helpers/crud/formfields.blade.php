	<div class="span4" style="display: none;">
		<div class="well text-center">
			<div class="well-title">
				Add/Update
				<div class="well-btn well-btn-right">
					<a href="javascript: void(0);" onClick="addPanel()"><i class="icon-remove"></i></a>
				</div>
			</div>
			{{ Form::open(array('id' => 'submitForm', 'files' => true)) }}
				<div class="control-group">
					<div class="controls">
						{{ Form::text('id', null, array('id' => 'id', 'readonly' => 'readonly', 'placeholder' => 'Existing Id', 'style' => 'margin-left: 14px;')) }}
						<a href="#helpModal" data-toggle="modal" class="icon-white icon-question-sign"></a>
					</div>
				</div>
				@foreach ($settings->formFields as $key => $details)
					@if ($details->field == 'image')
						<div class="fileupload fileupload-new" data-provides="fileupload" data-name="image">
							<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
								{{ HTML::image('img/noImage.gif', null, array('style' => 'width: 200px;')) }}
							</div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="line-height: 20px;"></div>
							<div>
								<span class="btn btn-file btn-primary">
									<span class="fileupload-new">Select image</span>
									<span class="fileupload-exists">Change</span>
									<input id="image" type="file" />
								</span>
								<a href="javascript: void(0);" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">Remove</a>
							</div>
						</div>
					@endif
					<div class="control-group" id="{{ $key }}">
						<div class="controls">
							@if ($details->field == 'text')
								{{ Form::text($key, null, array('id' => 'input_'. $key, 'placeholder' => (isset($details->placeholder) ? $details->placeholder : ucwords($key) ))) }}
							@elseif ($details->field == 'email')
								{{ Form::email($key, null, array('id' => 'input_'. $key, 'placeholder' => (isset($details->placeholder) ? $details->placeholder : ucwords($key) ))) }}
							@elseif ($details->field == 'textarea')
								{{ Form::textarea($key, null, array('id' => 'input_'. $key, 'placeholder' => (isset($details->placeholder) ? $details->placeholder : ucwords($key) ))) }}
							@elseif ($details->field == 'select')
								{{ Form::select($key, $details->selectArray, null, array('id' => 'input_'. $key)) }}
							@elseif ($details->field == 'multiselect')
								{{ Form::select($key .'[]', $details->selectArray, null, array('id' => 'input_'. $key, 'multiple' => 'multiple', 'style' => 'height: 200px;')) }}
							@endif
						</div>
					</div>
				@endforeach
				<div class="controls">
					{{ Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary', 'id' => 'jsonSubmit')) }}
				</div>
				<div id="message"></div>
			{{ Form::close(); }}
		</div>
	</div>