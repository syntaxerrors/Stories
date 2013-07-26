	<h3 class="text-primary">Select Story-Tellers</h3>
	@foreach($users as $user)
		@if ($user->id == $activeUser->id)
			<?php continue; ?>
		@endif
		<div class="control-group">
			<label class="control-label" for="{{ $user->username }}"><small>{{ $user->username }}</small></label>
			<div class="controls">
				{{ Form::checkbox('users['. $user->id .']', Input::old('users['. $user->id .']'), false, array('id' => $user->username)) }}
			</div>
		</div>
	@endforeach