			<div class="well">
				<!-- Start Top Title Bar -->
				<div class="well-title">
					{{ $post->icon }}
					{{ $post->name }}
					@if ($post->forumType == 'post')
						(Read {{ $post->views .' '. Str::plural('time', $post->views) }})
					@endif
					@if ($post->forumType == 'reply' || ($post->forumType == 'post' && $post->forum_post_type_id != Forum_Post::TYPE_LOCKED))
						<div class="well-btn well-btn-right">
							<a href="#replyField" onClick="$('#collapseReply').addClass('in');">Reply</a>&nbsp;|&nbsp;
							<a href="#replyField" onClick="addQuote(this);" data-quote-id="{{ $post->id }}" data-quote-name="{{ $post->name }}" data-quote-type="{{ $post->forumType }}">Quote</a>
							@if ($post->forumType == 'post' && Config::get('app.forumNews'))
								@if ($activeUser->checkPermission('PROMOTE_FRONT_PAGE'))
									@if ($post->frontPageFlag == 0)
										&nbsp;|&nbsp;<a href="/forum/post/modify/{{ $post->id }}/frontPageFlag/1">Promote</a>
									@else
										&nbsp;|&nbsp;<a href="/forum/post/modify/{{ $post->id }}/frontPageFlag/0">Demote</a>
									@endif
								@endif
							@endif
						</div>
					@endif
				</div>
				<!-- End Top Title Bar -->
				<!-- Start tabs -->
				<div class="tabbable tabs-right">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#post_{{ $post->id }}" data-toggle="tab">Post</a></li>
						@if ($gameMode)
							@if ($gameMode && $post->character_id != null && $post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME 
								&& ($post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION || $post->board->category->game->isStoryteller($activeUser->id) || $post->character_id == $activeUser->id))
								<li><a href="#character_{{ $post->id }}" data-toggle="tab">Character</a></li>
							@endif
						@endif
						@if ($post->forumType == 'post' && count($attachments) > 0)
							<li><a href="#attachments_{{ $post->id }}" data-toggle="tab">Attachments ({{ count($attachments) }})</a></li>
						@endif
						<li><a href="#user_{{ $post->id }}" data-toggle="tab">User</a></li>
						@if (count($post->history) > 0)
							<li><a href="#edits_{{ $post->id }}" data-toggle="tab">Edits ({{ count($post->history) }})</a></li>
						@endif
						@if ($post->moderatorLockedFlag > 0 && $activeUser->checkPermission('FORUM_MOD'))
							<li><a href="#moderation_{{ $post->id }}" data-toggle="tab">Moderation</a></li>
						@endif
						@if ($post->forumType == 'post' && $post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && ($activeUser->id == $post->user_id || $activeUser->checkPermission('DEVELOPER')))
							<li class="dropdown"><a href="javascript: void();" data-toggle="dropdown">Status <b class="caret"></b></a>
								<ul class="dropdown-menu">
									@if ($activeUser->checkPermission('DEVELOPER'))
										<li>
											<a href="javascript:void();" onClick="$.post('/forum/post/update/{{ $post->id }}/null/{{ Forum_Support_Status::TYPE_OPEN }}/status')">
												<i class="icon-bolt"></i> Open
											</a>
										</li>
										<li>
											<a href="javascript:void();" onClick="$.post('/forum/post/update/{{ $post->id }}/null/{{ Forum_Support_Status::TYPE_IN_PROGRESS }}/status')">
												<i class="icon-time"></i> In Progress
											</a>
										</li>
										<li>
											<a href="javascript:void();" onClick="$.post('/forum/post/update/{{ $post->id }}/null/{{ Forum_Support_Status::TYPE_RESOLVED }}/status')">
												<i class="icon-check"></i> Resolved
											</a>
										</li>
										<li>
											<a href="javascript:void();" onClick="$.post('/forum/post/update/{{ $post->id }}/null/{{ Forum_Support_Status::TYPE_WONT_FIX }}/status')">
												<i class="icon-ban-circle"></i> Wont Fix
											</a>
										</li>
									@elseif ($activeUser->id == $post->user_id)
										<li>
											<a href="javascript:void();" onClick="$.post('/forum/post/update/{{ $post->id }}/null/{{ Forum_Support_Status::TYPE_RESOLVED }}/status')">
												<i class="icon-check"></i> Resolved
											</a>
										</li>
									@endif
								</ul>
							</li>
						@endif
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="post_{{ $post->id }}">
							<div class="span2">
								@include('forum.post.components.user')
							</div>
							<div class="span10">
								@include('forum.post.components.postcontents')
							</div>
						</div>
						@if ($gameMode)
							<div class="tab-pane fade" id="character_{{ $post->id }}">
								<div class="span2">
									@include('forum.post.components.user')
								</div>
								<div class="span10">
									@include('forum.post.components.characterdetails')
								</div>
							</div>
						@endif
						@if ($post->forumType == 'post' && count($attachments) > 0)
							<div class="tab-pane fade" id="attachments_{{ $post->id }}">
								<div class="span2">
								@include('forum.post.components.user')
								</div>
								<div class="span10">
									@include('forum.post.components.attachments')
								</div>
							</div>
						@endif
						<div class="tab-pane fade" id="user_{{ $post->id }}">
							<div class="span2">
								@include('forum.post.components.user')
							</div>
							<div class="span10">
								@include('forum.post.components.userdetails')
							</div>
						</div>
						@if (count($post->history) > 0)
							<div class="tab-pane fade" id="edits_{{ $post->id }}">
								<div class="span2">
								@include('forum.post.components.user')
								</div>
								<div class="span10">
									@include('forum.post.components.edithistory')
								</div>
							</div>
						@endif
						@if ($post->moderatorLockedFlag > 0 && $activeUser->checkPermission('FORUM_MOD'))
							<div class="tab-pane fade" id="moderation_{{ $post->id }}">
								<div class="span2">
								@include('forum.post.components.user')
								</div>
								<div class="span10">
									@include('forum.post.components.moderation')
								</div>
							</div>
						@endif
					</div>
				</div>
				<!-- End tabs -->
				<!-- Start Bottom Bar -->
				<div class="well-title-bottom">
					@if ($gameMode)
						@if ($post->approvedFlag == 0 && $post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME &&
							( ($post->forumType == 'post' && $activeUser->checkPermission('GAME_MASTER') && $post->forum_post_type_id == Forum_Post::TYPE_APPLICATION) ||
							  ($post->forumType == 'reply' && $post->board->category->game->isStoryteller($activeUser->id) && $post->forum_reply_type_id == Forum_Reply::TYPE_ACTION) ) )
							{{ HTML::link('forum/post/modify/'. $post->id .'/approvedFlag/1/'. $post->forumType, 'Approve') }}
						@endif
					@endif
					@if ($activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN')) || $post->user_id == $activeUser->id)
						<div class="well-btn well-btn-danger well-btn-right">
							@if ($activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN')))
								{{ HTML::linkIcon('forum/post/delete/'. $post->id .'/'. $post->forumType, 'icon-trash', null, array('class' => 'confirm-remove', 'style' => 'color: #fff;font-size: 14px;')) }}
							@endif
						</div>
						@if ($post->moderatorLockedFlag != 1)
							<div class="well-btn well-btn-left">
								{{ HTML::linkIcon('forum/post/edit/'. $post->forumType .'/'. $post->id, 'icon-edit', null) }}
							</div>
						@endif
					@endif
				</div>
				<!-- End Bottom Bar -->
			</div>
			<!--End Post -->