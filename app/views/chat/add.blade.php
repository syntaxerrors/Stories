<div class="row-fluid">
	<div class="offset5 span3">
		<div class="well">
			<div class="well-title">Create new Chat Room</div>
			{{ Form::open() }}
				<div class="control-group">
					<label class="control-label" for="name">Name</label>
					<div class="controls">
						{{ Form::text('name', null, array('id' => 'name', 'placeholder' => 'Name')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="game_id">Game</label>
					<div class="controls">
						{{ Form::select('game_id', $games, array(), array('id' => 'game_id')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="activeFlag">Active</label>
					<div class="controls">
						{{ Form::checkbox('activeFlag', 1, true, array('id' => 'activeFlag')) }}
					</div>
				</div>
				<div class="controls">
					{{ Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary')) }}
				</div>
			{{ Form::close(); }}
		</div>
	</div>
</div>