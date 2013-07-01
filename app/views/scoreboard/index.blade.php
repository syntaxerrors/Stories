<div class="row-fluid">
	<div class="span4" id="config" style="display: none;">
		<div class="well">
			<div class="well-title">
				<div class="well-btn well-btn-right">
					<a href="javascript: void(0);" class="options"><i class="icon-remove"></i></a>
				</div>
				Options
			</div>
			<div class="row-fluid">
				<div class="offset2 span8">
					<button type="button" class="btn btn-primary span12" onClick="submitSearch(this);">Search</button>
				</div>
			</div>
			<hr />
			<div class="row-fluid text-center">
				@foreach ($series as $loopIndex => $seriesItem)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" id="series" value="{{ $seriesItem->keyName }}">{{ $seriesItem->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center" style="margin-bottom: 10px;">
				@foreach ($games as $loopIndex => $game)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" id="games" value="{{ $game->keyName }}">{{ $game->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center" style="margin-bottom: 10px;">
				@foreach ($teams as $loopIndex => $team)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" id="teams" value="{{ $team->id }}">{{ $team->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center" style="margin-bottom: 10px;">
				@foreach ($members as $loopIndex => $member)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" id="members" value="{{ $member->id }}">{{ $member->name }}</button></div>
				@endforeach
			</div>
			<hr />
		</div>
	</div>
	<div class="span12 no-sidebar" id="scores">
		<div id="scoreboard"></div>
	</div>
</div>
<script>
	@section('onReadyJs')
		$('#scoreboard').load('/scoreboard/getscores', { 'series': []});

	@stop
</script>
@section('js')
	<script>
		$('.options').click(function() {
			$('#config').toggle('slow');
			$('#scores').toggleClass('span12 span8');
			$('#scores').toggleClass('no-sidebar');
		});

		function submitSearch(object) {
			$(object).text('Searching...');
			$(object).attr('disabled', 'disabled');

			// Perform search and load data
			var series  = [];
			var games   = [];
			var teams   = [];
			var members = [];

			$('button#series').each(function() { 
				if ($(this).hasClass('active')) {
					series.push(this.value);
				}
			});

			$('button#games').each(function() { 
				if ($(this).hasClass('active')) {
					games.push(this.value);
				}
			});

			$('button#teams').each(function() { 
				if ($(this).hasClass('active')) {
					teams.push(this.value);
				}
			});

			$('button#members').each(function() { 
				if ($(this).hasClass('active')) {
					members.push(this.value);
				}
			});

			$('#scoreboard').load('/scoreboard/getscores', { 'series': series, 'games': games, 'teams': teams, 'members': members}, function() {
				$(object).removeAttr('disabled');
				$(object).text('Search');
			});
		}
	</script>
@stop