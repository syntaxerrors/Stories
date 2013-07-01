<div class="row-fluid">
	<div class="offset3 span6">
		<div class="well">
			<div class="well-title">Edit {{ $episode->game->name }}: {{ $episode->title }}</div>
			{{ Form::model($episode, array('class' => 'form-horizontal')) }}
				<div class="control-group">
					<label class="control-label" for="series_id">Series</label>
					<div class="controls">
						{{ Form::select('series_id', $series) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="game_id">Game</label>
					<div class="controls">
						{{ Form::select('game_id', $games) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="parentId">Parent Episode</label>
					<div class="controls">
						{{ Form::select('parentId', $episodes) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="seriesNumber">Episode Number</label>
					<div class="controls">
						{{ Form::text('seriesNumber') }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="title">Title</label>
					<div class="controls">
						{{ Form::text('title', stripslashes($episode->title)) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="link">YouTube Link</label>
					<div class="controls">
						{{ Form::text('link') }}<a href="http://www.youtube.com/watch?v={{ $episode->link }}" target="_blank"><i class="icon-large icon-youtube"></i></a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="date">Date</label>
					<div class="controls">
						<input type="date" name="date" id="date" value="{{ $episode->date }}" />
					</div>
				</div>
				<div class="controls">
					{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Add Another', array('class' => 'btn btn-small btn-primary', 'name' => 'continue')) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>