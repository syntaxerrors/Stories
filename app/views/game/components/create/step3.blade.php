	<h3 class="text-primary">Chat Room Options</h3>
	<div class="control-group">
		<label class="control-label" for="chatRooms"><small>Chat Rooms to Create</small></label>
		<div class="controls">
			{{ Form::text('chatRooms', Input::old('chatRooms'), array('id' => 'chatRooms')) }}
			<span class="inline-help text-disabled">Separate the name of the chat rooms with a comma (Leave blank to not create any rooms)</span>
		</div>
	</div>