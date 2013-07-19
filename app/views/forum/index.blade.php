<div class="row-fluid">
	<div class="span8">
		<small>
			<ul class="breadcrumb">
				<li class="active">Forums</li>
			</ul>
		</small>
		<?php $main = true; ?>
		@if (count($categories) > 0)
			@foreach ($categories as $category)
				@include('forum.category.view')
			@endforeach
		@endif
	</div>
	<div class="span4">
		<div class="well">
			<div class="well-title">Recent Activity</div>
			<table style="width: 100%;" class="table-hover">
				<tbody>
					@if (count($recentPosts) > 0)
						@foreach ($recentPosts as $post)
							@if ($post->board->forum_board_type_id == Forum_Board::TYPE_GM && !$activeUser->can('GAME_MASTER'))
								<?php continue; ?>
							@endif
							<tr>
								<td class="text-center" style="width: 30px;">
									@if (isset($post->status->id))
										{{ $post->status->icon }}
									@else
										{{ $post->icon }}
									@endif
								</td>
								<td>{{ HTML::link('forum/post/view/'. $post->keyName, $post->name) }}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
		@if (count($games) > 0)
			@foreach ($games as $game)
				<div class="well">
					<div class="well-title">{{ $game->name }} Activity</div>
					<table style="width: 100%;" class="table-hover">
						<tbody>
							@if (count($game->recentPosts) > 0)
								@foreach ($game->recentPosts as $post)
									<tr>
										<td class="text-center" style="width: 30px;">
											@if (isset($post->status->id))
												{{ $post->status->icon }}
											@else
												{{ $post->icon }}
											@endif
										</td>
										<td>{{ HTML::link('forum/post/view/'. $post->keyName, $post->name) }}</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			@endforeach
		@endif
		<div class="well">
			<div class="well-title">Technical Support</div>
			<table style="width: 100%;" class="table-hover">
				<caption>Issues</caption>
				<tbody>
					<tr class="text-info">
						<td class="text-center"><i class="icon-bolt"></i></td>
						<td><b>Open Issues</b></td>
						<td>{{ $openIssues }}</td>
					</tr>
					<tr class="text-warning">
						<td class="text-center"><i class="icon-time"></i></td>
						<td><b>In Progress Issues</b></td>
						<td>{{ $inProgressIssues }}</td>
					</tr>
					<tr class="text-success">
						<td class="text-center"><i class="icon-check"></i></td>
						<td><b>Resolved Issues</b></td>
						<td>{{ $resolvedIssues }}</td>
					</tr>
				</tbody>
			</table>
			<table style="width: 100%;" class="table-hover">
				<caption>Recent Posts</caption>
				<tbody>
					@if (count($recentSupportPosts) > 0)
						@foreach ($recentSupportPosts as $post)
							<tr>
								<td class="text-center" style="width: 30px;">{{ $post->status->icon }}</td>
								<td>{{ HTML::link('forum/post/view/'. $post->keyName, $post->name) }}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>