<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Customize your theme</div>
			{{ Form::open(array('id' => 'theme')) }}
				@foreach ($colors as $color => $values)
					<div class="control-group" id="{{ $color }}">
						<label class="control-label">{{ $values['title'] }}</label>
						<div class="controls ">
							<div class="input-append">
								<div class="colorPreview" id="{{ $color }}Preview" style="background-color: {{ $values['hex'] }}; width: 21px; height: 21px;display: inline-block; margin-right: 10px;">&nbsp;</div>
								{{ Form::text($color, $values['hex'], array('id' => $color .'Input', 'class' => 'colorpicker', 'style' => 'height: 19px;')) }}
							</div>
						</div>
					</div>
				@endforeach
				{{ Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'jsonSubmit')) }}
				<div id="message"></div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@section('jsInclude')
	{{ HTML::script('vendors/colorPicker/js/bootstrap-colorpicker.js') }}
@stop

<script>
	@section('onReadyJs')
		$('.colorpicker').colorpicker().on('changeColor', function(ev){
			$(this).closest('.colorPreview').css('background-color', ev.color.toHex());
		});
	@stop
</script>

@section('js')
	<script>
		$('#theme').AjaxSubmit({
			path:'/{{ Request::path() }}',
			successMessage:'Your theme has been updated.'
		});

		function changeColor(type) {
			if (type == 'grey') {
				var color = $('#greyInput').val();

				$('body').css('background-color', color);
			} else if (type == 'primary') {
				var color = $('#primaryInput').val();

				$('.primary').css('background-color', color);
			}
		}

		function revertColor(type, color) {
			if (type == 'grey') {
				$('#greyInput').val(color);
				$('body').css('background-color', color);
			}
		}
	</script>
@stop