<div class="row-fluid">
	<div class="span4">
		<div class="well">
			<div class="well-title">Options</div>
			<div class="row-fluid">
				<div class="offset2 span8">
					<button type="button" class="btn btn-primary span12" onClick="submitSearch(this);">Search</button>
				</div>
			</div>
			<hr />
			<div class="row-fluid text-center">
				<?php $seriesSpan = 'span'. 12 / $series->count(); ?>
				@foreach ($series as $seriesItem)
					<div class="{{ $seriesSpan }}"><button type="button" class="btn btn-primary span12" data-toggle="button" value="{{ $seriesItem->keyName }}">{{ $seriesItem->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center" style="margin-bottom: 10px;">
				@foreach ($games as $loopIndex => $game)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" value="{{ $game->keyName }}">{{ $game->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center" style="margin-bottom: 10px;">
				@foreach ($teams as $loopIndex => $team)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" value="{{ $team->keyName }}">{{ $team->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center" style="margin-bottom: 10px;">
				@foreach ($members as $loopIndex => $member)
					@if ($loopIndex % 2 == 0)
						</div>
						<div class="row-fluid text-center" style="margin-bottom: 10px;">
					@endif
					<div class="span6"><button type="button" class="btn btn-primary span12" data-toggle="button" value="{{ $member->keyName }}">{{ $member->name }}</button></div>
				@endforeach
			</div>
			<hr />
		</div>
	</div>
	<div class="span8">
		<div class="well">
			<div class="well-title">Scoreboard</div>
			<div id="scoreboard"></div>
		</div>
	</div>
</div>
@section('js')
	<script>
		function submitSearch(object) {
			$(object).text('Searching...');
			$(object).attr('disabled', 'disabled');

			// Perform search and load data

			$(object).removeAttr('disabled');
			$(object).text('Search');
		}
	</script>
@stop