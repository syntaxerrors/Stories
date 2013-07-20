<div class="offset2 span7">
	<div class="well">
		<div class="well-title">Add a new game</div>
		{{ Form::open(array('class' => 'form-horizontal')) }}
			<div class="control-group">
				<label class="control-label" for="game_type_id"><small>Game Universe</small></label>
				<div class="controls">
					{{ Form::select('game_type_id', $types, null, array('id' => 'game_type_id')) }}
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					{{ Form::text('name', null, array('id' => 'name', 'placeholder' => 'Name')) }}
    			</div>
    		</div>
			<div class="control-group">
				<div class="controls">
					{{ Form::textarea('description', null, array('id' => 'description', 'placeholder' => 'Description')) }}
    			</div>
    		</div>
			<div class="control-group">
				<label class="control-label" for="activeFlag">Active</label>
				<div class="controls">
					{{ Form::checkbox('activeFlag', 1, null, array('id' => 'activeFlag')) }}
    			</div>
    		</div>
			<div class="controls">
				{{ Form::submit('Add Game', array('class' => 'btn btn-small btn-primary')) }}
			</div>
		{{ Form::close() }}
	</div>
</div>