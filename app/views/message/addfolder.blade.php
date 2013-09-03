	<div class="control-group">
		<label class="control-label" for="receiver_id"><small>Parent Folder</small></label>
		<div class="controls">
			{{ Form::select('parent_id', $folders, $inbox, array('id' => 'parent_id')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="title"><small>Name</small></label>
		<div class="controls">
			{{ Form::text('name', null, array('id' => 'name', 'placeholder' => 'Name')) }}
		</div>
	</div>