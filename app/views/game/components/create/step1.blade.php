	<h3 class="text-primary">General Details</h3>
	<div class="control-group">
		<label class="control-label" for="game_type_id"><small>Game Universe</small></label>
		<div class="controls">
			{{ Form::select('game_type_id', $types, Input::old('game_type_id'), array('id' => 'game_type_id')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="name"><small>Name</small></label>
		<div class="controls">
			{{ Form::text('name', Input::old('name'), array('id' => 'name', 'placeholder' => 'Name')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="description"><small>Description</small></label>
		<div class="controls">
			{{ Form::textarea('description', Input::old('description'), array('id' => 'description', 'placeholder' => 'Description')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="activeFlag">Active</label>
		<div class="controls">
			{{ Form::checkbox('activeFlag', 1, Input::old('activeFlag'), array('id' => 'activeFlag')) }}
		</div>
	</div>