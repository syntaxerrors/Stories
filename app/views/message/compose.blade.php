	<div class="control-group">
		<label class="control-label" for="receiver_id"><small>To</small></label>
		<div class="controls">
			@if ($replyFlag == 0)
				{{ Form::select('receiver_id', $users, null, array('id' => 'receiver_id')) }}
			@else
				{{ Form::hidden('child_id', $message->id) }}
				{{ Form::hidden('receiver_id', $message->sender_id) }}
				{{ $message->sender->username }}
			@endif
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="title"><small>Title</small></label>
		<div class="controls">
			<?php
				$title = null;
				if (isset($message) && $message != null) {
					if (strpos($message->title, 'RE:') === false) {
						$title = 'RE: '. $message->title;
					} else {
						$title = $message->title;
					}
				}
			?>
			{{ Form::text('title', $title, array('id' => 'title', 'placeholder' => 'Title')) }}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="body"><small>Body</small></label>
		<div class="controls">
			{{ Form::textarea('content', null, array('id' => 'content', 'placeholder' => 'Body', 'style' => 'margin-left: 0; width: auto;', 'cols' => 80, 'rows' => 5)) }}
		</div>
	</div>
