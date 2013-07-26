	<h3 class="text-primary">Confirm Settings</h3>
	<h4 class="text-info">Game Settings</h4>
	<dl class="dl-horizontal">
		<dt>Game Name:</dt>
		<dd><div id="gameName"></div></dd>
	</dl>
	<dl class="dl-horizontal">
		<dt>Game Type:</dt>
		<dd><div id="gameType"></div></dd>
	</dl>
	<dl class="dl-horizontal">
		<dt>Description:</dt>
		<dd><div id="gameDescription"></div></dd>
	</dl>
	<dl class="dl-horizontal">
		<dt>Status at launch:</dt>
		<dd><div id="gameActive"></div></dd>
	</dl>
	<hr />
	<h4 class="text-info">Forum Settings</h4>
	<dl class="dl-horizontal">
		<dt>Forum Category:</dt>
		<dd><div id="categoryDetails"></div></dd>
	</dl>
	<dl class="dl-horizontal">
		<dt>Applications Board:</dt>
		<dd><div id="applicationDetails"></div></dd>
	</dl>
	<hr />
	<h4 class="text-info">Chat Settings</h4>
	<dl class="dl-horizontal">
		<dt>Chat Rooms:</dt>
		<dd><div id="chatRoomList"></div></dd>
	</dl>
	<hr />
	<h4 class="text-info">Story-Tellers</h4>
	<dl class="dl-horizontal">
		<dt>Story-Tellers at launch:</dt>
		<dd><div id="storyTellers"></div></dd>
	</dl>
	<hr />
	<h4 class="text-info">Configuration Details</h4>
	<dl class="dl-horizontal">
		<dt>Configuration Options:</dt>
		<dd><div id="configs"></div></dd>
	</dl>
	{{ Form::submit('Confirm Settings and Save', array('class' => 'btn btn-small btn-primary')) }}
	<br />
	<br />
@section('js')
	<script>
		$('#MyWizard').on('change', function(e, data) {
			if(data.step === 5) {
				var form = $('#submitForm').serialize();
				$.post('setgameoptions', form, function(results) {
					var results = $.parseJSON(results);
					$('#gameName').html(results.gameName);
					$('#gameType').html(results.gameType);
					$('#gameDescription').html(results.gameDescription);
					$('#gameActive').html(results.gameActive);
					$('#categoryDetails').html(results.categoryDetails);
					$('#applicationDetails').html(results.applicationDetails);
					$('#chatRoomList').html(results.chatRooms);
					$('#storyTellers').html(results.storyTellers);
					$('#configs').html(results.configs);
				});
			}
		});
	</script>
@stop