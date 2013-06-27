<div class="row-fluid">
	<div class="offset4 span4">
		{{ HTML:: link('/manage/add/1/4', 'Minecraft Lets Play', array('class' => 'btn btn-mini btn-primary')) }}
		{{ HTML:: link('/manage/add/3/4', 'Minecraft Things to Do', array('class' => 'btn btn-mini btn-primary')) }}
		{{ HTML:: link('/manage/add/6', 'Versus', array('class' => 'btn btn-mini btn-primary')) }}
	</div>
</div>
<div class="row-fluid">
	<div class="offset4 span4">
		<div class="well">
			<div class="well-title">Add New Episode</div>
			{{ Form::open(array('class' => 'form-horizontal')) }}
				<div class="control-group">
					<label class="control-label" for="series_id">Series</label>
					<div class="controls">
						{{ Form::select('series_id', $series, $seriesId, array('id' => 'series_id', 'required' => 'required')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="game_id">Game</label>
					<div class="controls">
						{{ Form::select('game_id', $games, $gameId, array('id' => 'game_id', 'required' => 'required')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="parentId">Parent Episode</label>
					<div class="controls">
						{{ Form::select('parentId', $episodes, array('id' => 'parentId')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="seriesNumber">Episode Number</label>
					<div class="controls">
						{{ Form::text('seriesNumber', null, array('id' => 'seriesNumber')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="title">Title</label>
					<div class="controls">
						{{ Form::text('title', null, array('id' => 'title', 'required' => 'required')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="link">YouTube Link</label>
					<div class="controls">
						{{ Form::text('link', null, array('id' => 'link', 'required' => 'required')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="date">Date</label>
					<div class="controls">
						<input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" />
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