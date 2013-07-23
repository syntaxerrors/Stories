<div class="row-fluid">
	<div class="offset1 span10">
		<div class="well">
			<div class="well-title">{{ $game->name }} Configurations</div>
			{{ Form::open(array('class' => 'form-horizontal')) }}
				@foreach ($configs as $config)
					<div class="control-group">
						<label class="control-label" for="{{ $config->id }}">{{ $config->name }}</label>
						<div class="controls">
							@if ($config->value == '.*')
								{{ Form::text($config->id, null, array('id' => $config->id)) }}
							@elseif ($config->value == '0|1')
								{{ Form::checkbox($config->id, null, array('id' => $config->id)) }}
							@endif
							<span class="help-inline" onClick="$('#configDescription').html('{{ $config->description }}');">
								<a href="#helpModal" data-toggle="modal" class="icon-white icon-question-sign"></a>
							</span>
						</div>
					</div>
				@endforeach
			{{ Form::close() }}
		</div>
	</div>
</div>
<div id="helpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Help</h3>
	</div>
	<div class="modal-body">
		<!-- <div class="well well-small"> -->
			<div id="configDescription"></div>
		<!-- </div> -->
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>