{{ HTML::script('vendors/slimScroll/jquery.slimscroll.min.js') }}
<div class="row-fluid">
	<div class="span8">
		<div class="well" style="height: 400px;">
			<strong class="text-info">Chat Room:</strong>&nbsp;<strong class="text-error">{{ $chatRoom->name }}</strong>
			<span class="pull-right text-info">{{ HTML::link('chat/fullChat/'. $chatRoom->uniqueId, 'Full Transcript', array('target' => '_blank', 'class' => "btn btn-mini btn-primary")) }}</span>
			<hr />
			<div id="chatBox">
			</div>
		</div>
	</div>
	<div class="span2">
		<div class="well" style="height: 400px;">
			<strong class="text-info">Users Online</strong>
			<div id="usersOnline">
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span10">
		{{ Form::hidden('character_id', 0, array('id' => 'character_id')) }}
		@if ($chatRoom->game_id != null || $chatRoom->game_template_id != null)
			<div class="input-append">
				{{ Form::hidden('character_id', 0, array('id' => 'character_id')) }}
				{{ Form::text('message_name', 'Posting as: '. $activeUser->username, array('id' => 'post_name', 'class' => 'span12 well', 'rows' => 3, 'readonly' => 'readonly')) }}
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
						Characters
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="javascript: void(0);" id="{{ $activeUser->id }}" onClick="setCharacter(0);" data-id="">{{ $activeUser->username }}</a></li>
						<li class="divider"></li>
						@if ($chatRoom->game_template_id != null)
							@if (count($activeUser->getTemplateCharacters($chatRoom->game_template_id)) > 0)
								@foreach ($activeUser->getTemplateCharacters($chatRoom->game_template_id) as $character)
									@if ($character->approved == 1 && $character->activeFlag == 1)
										<li><a href="javascript: void(0);" id="character_{{ $character->id }}" onClick="setCharacter({{ $character->id }});" data-id="{{ $character->id }}">{{ $character->name }}</a></li>
									@endif
								@endforeach
							@endif
						@elseif ($chatRoom->game_id != null)
							@if (count($activeUser->getGameCharacters($chatRoom->game_id)) > 0)
								@foreach ($activeUser->getGameCharacters($chatRoom->game_id) as $character)
									@if ($character->approved == 1 && $character->activeFlag == 1)
										<li><a href="javascript: void(0);" id="character_{{ $character->id }}" onClick="setCharacter({{ $character->id }});" data-id="{{ $character->id }}">{{ $character->name }}</a></li>
									@endif
								@endforeach
							@endif
						@endif
					</ul>
				</div>
			</div>
		@endif
		{{ Form::textarea('message', null, array('id' => 'message', 'placeholder' => 'Type a message', 'class' => 'well span12', 'rows' => 4)) }}
		<span class="help-inline">
			<table class="table">
				<tr>
					<td style="width: 33%;">
						Use Shift+Enter to make a new line.<br />
						Use Enter to submit your message.
					</td>
					@if ($chatRoom->game_id != null)
						<td style="width: 33%;">
							/roll to roll a D100<br />
							/<a href="javascript: void(0);" rel="popover" data-toggle="popover" data-placement="top" data-content="{{ implode('<br />',$skills) }}" data-html="true" title data-original-title="Examples">(Skill)</a> to see your skill stats (ex /Dance)<br />
							/spell <a href="javascript: void(0);" rel="popover" data-toggle="popover" data-placement="top" data-content="{{ implode('<br />',$spells) }}" data-html="true" title data-original-title="Examples">(Spell)</a> to cast your spell!
						</td>
						<td style="width: 33%;">
							/<a href="javascript: void(0);" rel="popover" data-toggle="popover" data-placement="top" data-content="{{ implode('<br />',$attributes) }}" data-html="true" title data-original-title="Examples">(Attribute)</a> to get your attribute and modifier!<br />
							/<a href="javascript: void(0);" rel="popover" data-toggle="popover" data-placement="top" data-content="{{ implode('<br />',$secondaries) }}" data-html="true" title data-original-title="Examples">(Secondary Attribute)</a> to see your value (ex /Attack)
						</td>
					@else					
						<td style="width: 33%;">&nbsp;</td>
						<td style="width: 33%;">&nbsp;</td>
					@endif
				</tr>
			</table>
		</span>
	</div>
</div>
<div id="output1"></div>
{{ HTML::script('vendors/titleAlert/jquery.titlealert.min.js') }}
{{ HTML::script('vendors/jwerty/jwerty.js') }}
@section('js')
<script type="text/javascript">
	function setCharacter(objectId) {
		var object   = $('#character_'+ objectId);
		var postId   = object.attr('data-id');
		var postName = object.html();
		if (postId == null) {
			$('#character_id').val(0);
			$('#post_name').val('Posting as: {{ $activeUser->username }}');
		} else {
			$('#character_id').val(postId);
			$('#post_name').val('Posting as: '+ postName);
		}
	}
	jwerty.key('enter', false);
	jwerty.key('enter', true, '#message');
	jwerty.key('enter', function () {
		var characterId = $('#character_id').val();
		var message = $('#message').val();
		$.post('/chat/addMessage/', { chat_room_id: {{ $chatRoom->uniqueId }},  character_id: characterId, message: message });
		$('#message').val('');
		$('#usersOnline').load('/chat/getUsersOnline/{{ $chatRoom->uniqueId }}');
	});

	function chatScroll() {
		$('#chatBox').slimScroll({
			height: '380px',
			railVisible: true,
			alwaysVisible: true,
			color: '#81aab0',
			scrollTo: $('#chatBox')[0].scrollHeight
		});
	};

	function sortChats() {
		// Get an array of all ticket rows in the table
		$($('#chatBox table').toArray().sort(function(a, b) {
			var date1 = Date.parse($(a).attr('data-date'));
			var date2 = Date.parse($(b).attr('data-date'));

			if (date1 > date2) {
				return 1;
			} else if(date1 == date2) {
				return 0;
			} else {
				return -1;
			}
		})).appendTo('#chatBox');
	}
</script>
@endsection