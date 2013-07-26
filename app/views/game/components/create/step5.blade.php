	<h3 class="text-primary">Game Configurations</h3>
	@foreach ($configs as $config)
		<div class="control-group">
			<label class="control-label" for="{{ $config->id }}">{{ $config->name }}</label>
			<div class="controls">
				@if ($config->value == '.*')
					{{ Form::text('configs['. $config->id .']', Input::old('configs['. $config->id .']'), array('id' => $config->id)) }}
				@elseif ($config->value == '0|1')
					{{ Form::checkbox('configs['. $config->id .']', Input::old('configs['. $config->id .']'), array('id' => $config->id)) }}
				@endif
				<span class="help-inline text-disabled">{{ $config->description }}</span>
			</div>
		</div>
	@endforeach