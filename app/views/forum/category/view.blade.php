 @if (!isset($main))
	<small>
		<ul class="breadcrumb">
			<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
			<li class="active">{{ $category->name }}</li>
		</ul>
	</small>
@endif
	<div class="well">
	<div class="well-title">
		{{ $category->name }}
		<div class="well-btn well-btn-left">
			{{ HTML::linkIcon('forum/category/view/'. $category->uniqueId, 'icon-share', null, array('style' => 'color: #000;')) }}
		</div>
		@if ($gameMode && $category->type->keyName == 'game')
			<div class="well-btn well-btn-right">
				{{ HTML::image('img/dice.png', null, array('style' => 'width: 14px;position: relative; bottom: 2px;')) }}
			</div>
		@elseif ($category->type->keyName == 'technical-support')
			<div class="well-btn well-btn-right">
				<i class="icon-cogs"></i>
			</div>
		@endif
	</div>
	@if (count($category->boards) > 0)
		@foreach ($category->boards as $board)
			@if ($gameMode && $board->forum_board_type_id == Forum_Board::TYPE_GM && !$activeUser->can('GAME_MASTER'))
				<?php continue; ?>
			@endif
			@if ($board->parent_id == null)
				<table style="width: 100%;">
					<tbody>
						<tr>
							<td class="middle" style="width: 65px;" rowpsan="3">
								@if ($activeUser->checkUnreadBoard($board->id))
									{{ HTML::image('img/forum/on.png', null, array('style' => 'width: 60px')) }}
								@else
									{{ HTML::image('img/forum/off.png', null, array('style' => 'width: 60px')) }}
								@endif
							</td>
							<td class="boardLink" rowpsan="3">
								<table>
									<tbody>
										<tr>
											<td>{{ HTML::link('forum/board/view/'. $board->uniqueId, $board->name) }}</td>
										</tr>
										@if ($board->childLinks != null)
											<tr>
												<td><small><small>Child Boards:&nbsp;{{ $board->childLinks }}</small></small></td>
											</tr>
										@endif
									</tbody>
								</table>
							</td>
							<td class="middle" style="width: 100px;">
								<table class="main no_border">
									<tbody>
										<tr>
											<td>{{ $board->postsCount .' '. Str::plural('Post', $board->postsCount) }}</td>
										</tr>
										<tr>
											<td>{{ $board->repliesCount .' '. Str::plural('Reply', $board->repliesCount) }}</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td style="width: 200px;">
								@if ($board->lastUpdate !== false)
									<?php
										$lastUpdatePage = $board->lastUpdatePage;
										if ($lastUpdatePage != null) {
											$lastUpdateType = $board->lastUpdate->type->keyName;
											$lastUpdateUser = ($board->lastUpdate->character_id == null || $lastUpdateType == 'application'
												? $board->lastUpdate->author : $board->lastUpdate->character);
											$lastUpdateName = ($lastUpdateUser instanceof User ? $lastUpdateUser->username : $lastUpdateUser->name);
											$lastUpdateLink = 'forum/post/view/'. $board->lastPost->uniqueId;
											if ($lastUpdatePage > 1) {
												$lastUpdateLink .= '?page='. $lastUpdatePage;
											}
										}
										$lastUpdateLink .=  '#reply:'. $board->lastUpdate->id;
									?>
									<small>
										<table>
											<tbody>
												<tr>
													<td>Last Post by {{ HTML::link('profile/user/'. $board->lastUpdate->author->id, $lastUpdateName) }}</td>
												</tr>
												<tr>
													<td>in {{ HTML::link($lastUpdateLink, $board->lastUpdate->name) }}</td>
												</tr>
												<tr>
													<td>on {{ $board->lastUpdate->created_at }}</td>
												</tr>
											</tbody>
										</table>
									</small>
								@else
									<small>
										No posts.
									</small>
								@endif
							</td>
						</tr>
					</tbody>
				</table>
				<hr />
			@endif
		@endforeach
	@endif
</div>
@if (!isset($main))
	<small>
		<ul class="breadcrumb">
			<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
			<li class="active">{{ $category->name }}</li>
		</ul>
	</small>
@endif