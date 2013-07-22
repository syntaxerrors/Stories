<div class="row-fluid">
	<div class="span10">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				<li>{{ HTML::link('forum/category/view/'. $board->category->uniqueId, $board->category->name) }} <span class="divider">/</span></li>
				@if ($board->parent != null)
					<li>{{ HTML::link('forum/board/view/'. $board->parent->uniqueId, $board->parent->name) }} <span class="divider">/</span></li>
				@endif
				<li class="active">
					{{ $board->name }}
					@if (count($posts) == 30 || isset($_GET['page']))
						<?php
							if (isset($_GET['page'])) {
								$page = $_GET['page'];
							} else {
								$page = 1;
							}
						?>
						: Page {{ $page }}
					@endif
				</li>
			</ul>
		</small>
		@if (count($board->children) > 0)
			<div class="well">
				<div class="well-title">Child Boards</div>
				@foreach ($board->children as $child)
					<table style="width: 100%;">
						<tbody>
							<tr>
								<td class="middle" style="width: 65px;" rowpsan="3">
									@if (Auth::user()->checkUnreadBoard($child->id))
										{{ HTML::image('img/forum/on.png', null, array('style' => 'width: 30px')) }}
									@else
										{{ HTML::image('img/forum/off.png', null, array('style' => 'width: 30px')) }}
									@endif
								</td>
								<td class="boardLink" rowpsan="3">
									<table>
										<tbody>
											<tr>
												<td>{{ HTML::link('forum/board/view/'. $child->uniqueId, $child->name) }}</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td class="middle" style="width: 100px;">
									<table class="main no_border">
										<tbody>
											<tr>
												<td>{{ $child->postsCount .' '. Str::plural('Post', $child->postsCount) }}</td>
											</tr>
											<tr>
												<td>{{ $child->repliesCount .' '. Str::plural('Reply', $child->repliesCount) }}</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="width: 200px;">
									@if ($child->lastUpdate !== false)
										<?php
											$lastUpdateType = $child->lastUpdate->type->keyName;
											$lastUpdateUser = ($child->lastUpdate->character_id == null || $lastUpdateType == 'application'
												? $child->lastUpdate->author : $child->lastUpdate->character);
											$lastUpdateName = ($lastUpdateUser instanceof User ? $lastUpdateUser->username : $lastUpdateUser->name);
										?>
										<small>
											<table>
												<tbody>
													<tr>
														<td>Last Post by {{ HTML::link('profile/user/'. $child->lastUpdate->author->id, $lastUpdateName) }}</td>
													</tr>
													<tr>
														<td>in {{ HTML::link('forum/post/view/'. $child->lastPost->uniqueId .'#reply:'. $child->lastUpdate->id, $child->lastUpdate->name) }}</td>
													</tr>
													<tr>
														<td>on {{ $child->lastUpdate->created_at }}</td>
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
				@endforeach
			</div>
		@endif
		<div class="well">
			<div>
				@if (count($posts) == 30 || isset($_GET['page']))
					<?php
						if (isset($_GET['page'])) {
							$page = $_GET['page'];
						} else {
							$page = 1;
						}
					?>
					<div style="vertical-align: top;display: inline-block;">
						<span class="muted">Page:</span>&nbsp;
					</div>
					<div style="display: inline-block;">{{ $posts->links(3, 'pagination-mini', false) }}</div>
				@endif
			</div>
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th style="width: 8%;" colspan="2">&nbsp;</th>
						<th class="text-left">Subject/Author</th>
						<th class="text-center" style="width: 14%;">Replies/Views</th>
						<th style="width:22%;" class="text-left">Last Post</th>
					</tr>
				</thead>
				<tbody>
					@if (count($announcements) > 0)
						@foreach ($announcements as $announcement)
							<tr class="error">
								<td class="text-center text-middle">{{ $announcement->icon }}</td>
								<td>
									@if (!$announcement->checkUserViewed($activeUser->id))
										<i class="icon-eye-open" title="New"></i>
									@else
										&nbsp;
									@endif
								</td>
								<td>
									{{ HTML::link('forum/post/view/'. $announcement->uniqueId, $announcement->name) }}<br />
									<small>Started by {{ HTML::link('profile/user/'. $announcement->author->id, $announcement->author->username) }}</small>
								</td>
								<td class="text-center">
									<small>
										{{ $announcement->repliesCount .' '. Str::plural('Reply', $announcement->repliesCount) }}
										<br />
										{{ $announcement->views .' '. Str::plural('View', $announcement->views) }}
									</small>
								</td>
								<td>
									<?php
										$lastUpdateType = $announcement->lastUpdate->type->keyName;
										$lastUpdateUser = ($announcement->lastUpdate->character_id == null || $lastUpdateType == 'application'
											? $announcement->lastUpdate->author : $announcement->lastUpdate->character);
										$lastUpdateName = ($lastUpdateUser instanceof User ? $lastUpdateUser->username : $lastUpdateUser->name);
									?>
									<small>
										{{ $announcement->lastUpdate->created_at }}<br />
										by {{ HTML::link('profile/user/'. $announcement->lastUpdate->author->id, $lastUpdateName) }}
									</small>
								</td>
						@endforeach
					@endif
					@if (count($posts) > 0)
						@foreach ($posts as $post)
							<tr>
								<td class="text-center text-middle">
									@if ($board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT)
										{{ $post->status->icon }}
									@else
										{{ $post->icon }}
									@endif
								</td>
								<td>
									@if (!$post->checkUserViewed($activeUser->id))
										<i class="icon-eye-open" title="New"></i>
									@else
										&nbsp;
									@endif
								</td>
								<td>
									{{ HTML::link('forum/post/view/'. $post->id, $post->name) }}
									@if ($post->forum_post_type_id == Forum_Post::TYPE_APPLICATION && $post->approvedFlag == 0)
										{{ HTML::link('forum/post/modify/'. $post->id .'/approvedFlag/1', 'Unapproved', array('class' => 'label label-important')) }}
									@endif
									<br />
									<small>Started by {{ HTML::link('profile/user/'. $post->author->id, $post->author->username) }}</small>
								</td>
								<td class="text-center">
									<small>
										{{ $post->repliesCount .' '. Str::plural('Reply', $post->repliesCount) }}
										<br />
										{{ $post->views .' '. Str::plural('View', $post->views) }}
									</small>
								</td>
								<td>
									<?php
										$lastUpdateType = $post->lastUpdate->type->keyName;
										$lastUpdateUser = ($post->lastUpdate->character_id == null || $lastUpdateType == 'application'
											? $post->lastUpdate->author : $post->lastUpdate->character);
										$lastUpdateName = ($lastUpdateUser instanceof User ? $lastUpdateUser->username : $lastUpdateUser->name);
									?>
									<small>
										{{ $post->lastUpdate->created_at }}<br />
										by {{ HTML::link('profile/user/'. $post->lastUpdate->author->id, $lastUpdateName) }}
									</small>
								</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
			<div>
				@if (count($posts) == 30 || isset($_GET['page']))
					<?php
						if (isset($_GET['page'])) {
							$page = $_GET['page'];
						} else {
							$page = 1;
						}
					?>
					<div style="vertical-align: top;display: inline-block;">
						<span class="muted">Page:</span>&nbsp;
					</div>
					<div style="display: inline-block;">{{ $posts->links(3, 'pagination-mini', false) }}</div>
				@endif
			</div>
		</div>
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				<li>{{ HTML::link('forum/category/view/'. $board->category->uniqueId, $board->category->name) }} <span class="divider">/</span></li>
				<li class="active">{{ $board->name }}</li>
			</ul>
		</small>
	</div>
	<div class="span2">
		<div class="well">
			<div class="well-title">Legend</div>
			<table style="width: 100%;" class="table-hover">
				<caption>Posts and Replies</caption>
				<tbody>
					<tr>
						<td class="text-center"><i class="icon-eye-open"></i></td>
						<td>Unread Post</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-warning-sign"></i></td>
						<td>Announcement</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-pushpin"></i></td>
						<td>Sticky</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-lock"></i></td>
						<td>Locked</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-inbox"></i></td>
						<td>Application</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-exchange"></i></td>
						<td>Action</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-comments"></i></td>
						<td>Conversation</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-cloud"></i></td>
						<td>Inner Thought</td>
					</tr>
				</tbody>
			</table>
			<br />
			<table style="width: 100%;" class="table-hover">
				<caption>Technical Support Issues</caption>
				<tbody>
					<tr>
						<td class="text-center"><i class="icon-bolt"></i></td>
						<td>Open</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-time"></i></td>
						<td>In Progress</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-check"></i></td>
						<td>Resolved</td>
					</tr>
					<tr>
						<td class="text-center"><i class="icon-ban-circle"></i></td>
						<td>Wont Fix<br />
					</tr>
				</tbody>
			</table>
		</div>
		@if ($board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT)
			<div class="well">
				<div class="well-title">Technical Support</div>
				<table style="width: 100%;" class="table-hover">
					<caption>Issues</caption>
					<tbody>
						<tr class="text-info">
							<td class="text-center"><i class="icon-bolt"></i></td>
							<td><b>Open</b></td>
							<td>{{ $openIssues }}</td>
						</tr>
						<tr class="text-warning">
							<td class="text-center"><i class="icon-time"></i></td>
							<td><b>In Progress</b></td>
							<td>{{ $inProgressIssues }}</td>
						</tr>
						<tr class="text-success">
							<td class="text-center"><i class="icon-check"></i></td>
							<td><b>Resolved</b></td>
							<td>{{ $resolvedIssues }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		@endif
	</div>
</div>