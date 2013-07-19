<div class="row-fluid">
	<div class="span10">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				<li>{{ HTML::link('forum/category/view/'. $board->category->keyName, $board->category->name) }} <span class="divider">/</span></li>
				<li>{{ HTML::link('forum/board/view/'. $board->keyName, $board->name) }} <span class="divider">/</span></li>
				<li class="active">Add Post</li>
			</ul>
		</small>
		<div class="well">
			<div class="well-title">New post</div>
			<div class="rowspan">
				{{ Form::open() }}
					<div class="control-group">
						<div class="controls text-center">
							{{ Form::select('forum_post_type_id', $types, array(1), array('class' => 'span10')) }}
						</div>
					</div>
					@if ($board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $board->type->keyName != 'application')
						<div class="control-group">
							<div class="controls text-center">
								{{ Form::select('character_id', $characters, array($primaryCharacter->id), array('class' => 'span10')) }}
							</div>
						</div>
					@endif
					<div class="control-group">
						<div class="controls text-center">
							{{ Form::text('name', null, array('placeholder' => 'Title', 'class' => 'span10', 'tabindex' => 1)) }}
						</div>
					</div>
					<?php $content =null; ?>
					@include('forum.post.components.content')
					<div class="controls text-center">
						{{ Form::submit('Post', array('class' => 'btn btn-small btn-primary span3', 'tabindex' => 3)) }}
					</div>
				{{ Form::close() }}
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>