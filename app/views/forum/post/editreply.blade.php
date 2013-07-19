<div class="row-fluid">
	<div class="span10">
		<div class="well">
			<div class="well-title">Edit post</div>
			<div class="rowspan">
				{{ Form::open() }}
					@if ($reply->forum_reply_type_id != Forum_Reply::TYPE_ACTION || $reply->board->category->game->isStoryteller($reply->author->id))
						<div class="control-group">
							<div class="controls text-center">
								{{ Form::select('forum_reply_type_id', $types, array($reply->forum_reply_type_id), array('class' => 'span10')) }}
							</div>
						</div>
					@endif
					@if ($reply->post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION && $reply->post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME)
						<div class="control-group">
							<div class="controls text-center">
								{{ Form::select('character_id', $characters, array($reply->character_id), array('class' => 'span10')) }}
							</div>
						</div>
					@endif
					<div class="control-group">
						<div class="controls text-center">
							{{ Form::text('name', $reply->name, array('placeholder' => 'Title', 'class' => 'span10')) }}
						</div>
					</div>
					<?php $content = $reply->content; ?>
					@include('forum.post.components.content')
					<div class="control-group">
						<div class="controls text-center">
							{{ Form::text('reason', null, array('placeholder' => 'Reason for edit', 'class' => 'span10')) }}
						</div>
					</div>
					<div class="controls text-center">
						{{ Form::submit('Post', array('class' => 'btn btn-small btn-primary span3')) }}
					</div>
				{{ Form::close() }}
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>