	<h3 class="text-primary">Forum Options</h3>
	<div class="control-group">
		<label class="control-label" for="addCategoryFlag"><small>Add Game Category</small></label>
		<div class="controls">
			{{ Form::checkbox('addCategoryFlag', 1, Input::old('addCategoryFlag'), array('id' => 'addCategoryFlag')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="addCategoryName"><small>Forum Category Name</small></label>
		<div class="controls">
			{{ Form::text('addCategoryName', Input::old('addCategoryName'), array('id' => 'addCategoryName')) }}
			<span class="inline-help text-disabled">Leave blank to use your game name as the title. (Assuming that the above checkbox is checked)</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="addApplicationBoardFlag"><small>Add Application Board</small></label>
		<div class="controls">
			{{ Form::checkbox('addApplicationBoardFlag', 1, Input::old('addApplicationBoardFlag'), array('id' => 'addApplicationBoardFlag')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="addApplicationBoardName"><small>Application Board Name</small></label>
		<div class="controls">
			{{ Form::text('addApplicationBoardName', Input::old('addApplicationBoardName'), array('id' => 'addApplicationBoardName')) }}
			<span class="inline-help text-disabled">Leave blank to use the default (Board will be called 'Applications')</span>
		</div>
	</div>