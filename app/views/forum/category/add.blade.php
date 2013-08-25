{{ Form::open() }}
	<div class="row-fluid">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				<li class="active">Add Category</li>
			</ul>
		</small>
		<div class="offset3 span6">
			<div class="well text-center">
				<div class="well-title">Add new forum category</div>
				@if ($gameMode)
					<div class="control-group">
						<div class="controls">
							{{ Form::text('name', ($game != null ? $game->name : Input::old('name')), array('placeholder' => 'Name')) }}
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							{{ Form::select('forum_category_type_id', $types, ($game != null ? array(2) : Input::old('forum_category_type_id'))) }}
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							{{ Form::select('game_id', $games, ($game != null ? array($game->id) : Input::old('game_id'))) }}
						</div>
					</div>
				@else
					<div class="control-group">
						<div class="controls">
							{{ Form::text('name', Input::old('name'), array('placeholder' => 'Name')) }}
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							{{ Form::select('forum_category_type_id', $types, Input::old('forum_category_type_id')) }}
						</div>
					</div>
				@endif
				<div class="control-group">
					<div class="controls">
						{{ Form::select('position', $categories, Input::old('position')) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::textarea('description', Input::old('description'), array('placeholder' => 'Description')) }}
					</div>
				</div>
				<div class="controls">
					{{ Form::submit('Add Category', array('class' => 'btn btn-small btn-primary')) }}
				</div>
			</div>
		</div>
	</div>
{{ Form::close() }}