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
				@foreach ($series as $seriesItem)
					<div class="span4"><button type="button" class="btn btn-primary" data-toggle="button" value="{{ $seriesItem->keyName }}">{{ $seriesItem->name }}</button></div>
				@endforeach
			</div>
			<hr />
			<div class="row-fluid text-center">
				<div class="span12">
					<div class="btn-group">
						@foreach ($games as $loopIndex => $game)
							@if ($loopIndex % 2 == 2)
										</div>
									</div>
								</div>
								<br />
							@elseif ($loopIndex % 2 == 0)
								<div class="row-fluid text-center">
									<div class="span12">
										<div class="btn-group">
							@endif
							<button type="button" class="btn btn-primary" data-toggle="button" value="{{ $game->keyName }}">{{ $game->name }}</button>
						@endforeach
					</div>
				</div>
			</div>
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