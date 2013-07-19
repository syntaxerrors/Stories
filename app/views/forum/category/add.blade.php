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
				<div class="control-group">
					<div class="controls">
						{{ Form::text('name', ($game != null ? $game->name : null), array('placeholder' => 'Name')) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::select('forum_category_type_id', $types, ($game != null ? array(2) : null)) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::select('position', $categories) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::select('game_id', $games, ($game != null ? array($game->id) : null)) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::textarea('description', null, array('placeholder' => 'Description')) }}
					</div>
				</div>
				<div class="controls">
					{{ Form::submit('Add Category', array('class' => 'btn btn-small btn-primary')) }}
				</div>
			</div>
		</div>
	</div>
{{ Form::close() }}