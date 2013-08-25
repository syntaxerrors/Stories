<div class="row-fluid">
	<div class="span11">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				<li>{{ HTML::link('forum/category/view/'. $post->board->category->id, $post->board->category->name) }} <span class="divider">/</span></li>
				@if ($post->board->parent != null)
					<li>{{ HTML::link('forum/board/view/'. $post->board->parent->id, $post->board->parent->name) }} <span class="divider">/</span></li>
				@endif
				<li>{{ HTML::link('forum/board/view/'. $post->board->id, $post->board->name) }} <span class="divider">/</span></li>
				<li class="active">
					{{ $post->name }}					
					@if (count($replies) == 30 || isset($_GET['page']))
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
		<div>
			<div style="vertical-align: top;display: inline-block;">
				<span class="muted">Page:</span>&nbsp;
			</div>
			<div style="display: inline-block;">{{ $replies->links(3, 'pagination-mini', false) }}</div>
		</div>
		<div class="pull-left">
			<div class="btn-group">
				@if ($post->previousPost != null)
					{{ HTML::link('forum/post/view/'. $post->previousPost->id, $post->previousPost->name, array('class' => 'btn btn-mini btn-primary')) }}
				@endif
			</div>
		</div>
		<div class="pull-right">
			<div class="btn-group">
				@if ($post->nextPost != null)
					{{ HTML::link('forum/post/view/'. $post->nextPost->id, $post->nextPost->name, array('class' => 'btn btn-mini btn-primary')) }}
				@endif
			</div>
		</div>
		<div class="clearfix"></div>
		@if (!isset($_GET['page']) || $_GET['page'] == 1)
			<div class="well">
				<div class="well-title">
					@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $post->forum_post_type_id != Forum_Post::TYPE_ANNOUNCEMENT)
						{{ $post->status->icon }}
					@else
						{{ $post->icon }}
					@endif
					{{ $post->name }} (Read {{ $post->views .' '. Str::plural('time', $post->views) }})
					@if ($post->forum_post_type_id != Forum_Post::TYPE_LOCKED)
						<div class="well-btn well-btn-right">
							<a href="#replyField" onClick="$('#collapseReply').addClass('in');">Reply</a>&nbsp;|&nbsp;
							<a href="#replyField" onClick="addQuote(this);" data-quote-id="{{ $post->id }}" data-quote-name="{{ $post->name }}" data-quote-type="post">Quote</a>
							@if (Config::get('app.forumNews'))
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
				<div class="tabbable tabs-right">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#post_{{ $post->id }}" data-toggle="tab">Post</a></li>
						@if ($gameMode)
							@if ($post->character_id != null && $post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && ($post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION || $post->board->category->game->isStoryteller($activeUser->id) || $post->character_id == $activeUser->id))
								<li><a href="#character_{{ $post->id }}" data-toggle="tab">Character</a></li>
							@endif
						@endif
						@if (count($attachments) > 0)
							<li><a href="#attachments_{{ $post->id }}" data-toggle="tab">Attachments ({{ count($attachments) }})</a></li>
						@endif
						<li><a href="#user_{{ $post->id }}" data-toggle="tab">User</a></li>
						@if (count($post->history) > 0)
							<li><a href="#edits_{{ $post->id }}" data-toggle="tab">Edits ({{ count($post->history) }})</a></li>
						@endif
						@if ($post->moderatorLockedFlag > 0 && $activeUser->checkPermission('FORUM_MOD'))
							<li><a href="#moderation_{{ $post->id }}" data-toggle="tab">Moderation</a></li>
						@endif
						@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && ($activeUser->id == $post->user_id || $activeUser->checkPermission('DEVELOPER')))
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
						@if (count($attachments) > 0)
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
				<div class="well-title-bottom">
					@if ($gameMode)
						@if ($activeUser->checkPermission('GAME_MASTER') && $post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->forum_post_type_id == Forum_Post::TYPE_APPLICATION && $post->approvedFlag == 0)
							{{ HTML::link('forum/post/modify/'. $post->id .'/approvedFlag/1', 'Approve') }}
						@endif
					@endif
					@if ($activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN')) || $post->user_id == $activeUser->id)
						<div class="well-btn well-btn-danger well-btn-right">
							@if ($activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN')))
								{{ HTML::linkIcon('forum/post/delete/'. $post->id, 'icon-trash', null, array('class' => 'confirm-remove', 'style' => 'color: #fff;font-size: 14px;')) }}
							@endif
						</div>
						<div class="well-btn well-btn-left">
							{{ HTML::linkIcon('forum/post/edit/post/'. $post->id, 'icon-edit', null) }}
						</div>
					@endif
				</div>
			</div>
		@endif
		@if (count($replies) > 0)
			@foreach ($replies as $reply)
				<div class="well">
					<div class="well-title">
						{{ $reply->icon }}
						{{ $reply->name }}
						<div class="well-btn well-btn-right">
							<a href="#replyField" onClick="$('#collapseReply').addClass('in');">Reply</a>&nbsp;|&nbsp;
							<a href="#replyField" onClick="addQuote(this);" data-quote-id="{{ $reply->id }}" data-quote-name="{{ $reply->name }}" data-quote-type="reply">Quote</a>
						</div>
					</div>
					<div class="tabbable tabs-right">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#post_{{ $reply->id }}" data-toggle="tab">Post</a></li>
							@if ($gameMode)
								@if ($reply->post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $reply->character_id != null && ($reply->post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION || $post->board->category->game->isStoryteller($activeUser->id)))
									<li><a href="#character_{{ $reply->id }}" data-toggle="tab">Character</a></li>
								@endif
							@endif
							<li><a href="#user_{{ $reply->id }}" data-toggle="tab">User</a></li>
							@if (count($reply->history) > 0)
								<li><a href="#edits_{{ $reply->id }}" data-toggle="tab">Edits ({{ count($reply->history) }})</a></li>
							@endif
							@if ($reply->moderatorLockedFlag > 0 && $activeUser->checkPermission('FORUM_MOD'))
								<li><a href="#moderation_{{ $reply->id }}" data-toggle="tab">Moderation</a></li>
							@endif
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="post_{{ $reply->id }}">
								<div class="span2">
									@include('forum.post.components.user', array('post' => $reply))
								</div>
								<div class="span10">
									@include('forum.post.components.postcontents', array('post' => $reply))
								</div>
							</div>
							@if ($gameMode)
								<div class="tab-pane fade" id="character_{{ $reply->id }}">
									<div class="span2">
										@include('forum.post.components.user', array('post' => $reply))
									</div>
									<div class="span10">
										@include('forum.post.components.characterdetails', array('post' => $reply))
									</div>
								</div>
							@endif
							<div class="tab-pane fade" id="user_{{ $reply->id }}">
								<div class="span2">
									@include('forum.post.components.user', array('post' => $reply))
								</div>
								<div class="span10">
									@include('forum.post.components.userdetails', array('post' => $reply))
								</div>
							</div>
							@if (count($reply->history) > 0)
								<div class="tab-pane fade" id="edits_{{ $reply->id }}">
									<div class="span2">
									@include('forum.post.components.user', array('post' => $reply))
									</div>
									<div class="span10">
										@include('forum.post.components.edithistory', array('post' => $reply))
									</div>
								</div>
							@endif
							@if ($reply->moderatorLockedFlag > 0 && $activeUser->checkPermission('FORUM_MOD'))
								<div class="tab-pane fade" id="moderation_{{ $reply->id }}">
									<div class="span2">
									@include('forum.post.components.user')
									</div>
									<div class="span10">
										@include('forum.post.components.moderation', array('post' => $reply))
									</div>
								</div>
							@endif
						</div>
					</div>
					<div class="well-title-bottom">
						@if ($gameMode)
							@if ($reply->post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $reply->post->board->category->game->isStoryteller($activeUser->id) && $reply->forum_reply_type_id == Forum_Reply::TYPE_ACTION && $reply->approvedFlag == 0)
								{{ HTML::link('forum/post/modify/'. $reply->id .'/approvedFlag/1/reply', 'Approve') }}
							@endif
						@endif
						@if ($activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN')) || $reply->user_id == $activeUser->id)
							<div class="well-btn well-btn-danger well-btn-right">
								@if ($activeUser->checkPermission(array('DEVELOPER', 'FORUM_MOD', 'FORUM_ADMIN')))
									{{ HTML::linkIcon('forum/post/delete/'. $reply->id .'/reply', 'icon-trash', null, array('class' => 'confirm-remove', 'style' => 'color: #fff;font-size: 14px;')) }}
								@endif
							</div>
							@if ($reply->moderatorLockedFlag != 1)
								<div class="well-btn well-btn-left">
									{{ HTML::linkIcon('forum/post/edit/reply/'. $reply->id, 'icon-edit', null) }}
								</div>
							@endif
						@endif
					</div>
				</div>
			@endforeach
			<div>
				<div style="vertical-align: top;display: inline-block;">
					<span class="muted">Page:</span>&nbsp;
				</div>
				<div style="display: inline-block;">{{ $replies->links(3, 'pagination-mini', false) }}</div>
			</div>
			<br />
		@endif
		@if ($post->forum_post_type_id == Forum_Post::TYPE_LOCKED && $activeUser->getHighestRole('Forum') != 'Forum - Administrator')
			This board is locked for replies.
		@else
		{{ Form::open() }}
			<a name="reply"></a>
			<div class="well">
				<div class="well-title">
					<a class="accordion-toggle" data-toggle="collapse" href="#collapseReply" style="color: #000;" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
						Quick Reply <i class="icon-chevron-down"></i>
					</a>
				</div>
				<div id="collapseReply" class="accordion-body collapse">
					@if (!$activeUser->checkPermission('FORUM_POST'))
						You do not have permission to post replies.
					@else
						<div class="control-group">
							<div class="controls text-center">
								{{ Form::hidden('quote_id', null, array('id' => 'quote_id')) }}
								{{ Form::hidden('quote_type', null, array('id' => 'quote_type')) }}
								{{ Form::text('quote', null, array('id' => 'quote', 'readonly' => 'readonly', 'placeholder' => 'Quoted Post', 'class' => 'span10')) }}
							</div>
						</div>
						<div class="control-group">
							<div class="controls text-center">
								{{ Form::select('forum_reply_type_id', $types, array(1), array('class' => 'span10')) }}
							</div>
						</div>
						@if ($gameMode)
							@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->forum_post_type_id != Forum_Post::TYPE_APPLICATION)
								<div class="control-group">
									<div class="controls text-center">
										{{ Form::select('character_id', $characters, array($primaryCharacter->id), array('class' => 'span10')) }}
									</div>
								</div>
							@endif
						@endif
						<div class="control-group">
							<div class="controls text-center">
								{{ Form::text('name', null, array('placeholder' => 'Title', 'class' => 'span10', 'tabindex' => 1)) }}
							</div>
						</div>
						@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $activeUser->checkPermission('DEVELOPER'))
							<div class="control-group">
								<div class="controls text-center">
									{{ Form::select('forum_support_status_id', $statuses, null, array('class' => 'span10')) }}
								</div>
							</div>
						@elseif ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $activeUser->id == $post->user_id)
							@if ($post->status->status->id != Forum_Support_Status::TYPE_RESOLVED)
								<div class="control-group">
									<div class="controls text-center">
										{{ Form::select('forum_support_status_id', array(0 => 'Select a status', Forum_Support_Status::TYPE_RESOLVED => 'Resolved'), null, array('class' => 'span10')) }}
									</div>
								</div>
							@endif
						@endif
						<?php $content = null; ?>
						@include('forum.post.components.content')
						<div class="controls text-center">
							{{ Form::submit('Post', array('class' => 'btn btn-small btn-primary span3', 'tabindex' => 3)) }}
						</div>
					@endif
				</div>
			</div>
		@endif
		{{ Form::close() }}
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				<li>{{ HTML::link('forum/category/view/'. $post->board->category->id, $post->board->category->name) }} <span class="divider">/</span></li>
				@if ($post->board->parent != null)
					<li>{{ HTML::link('forum/board/view/'. $post->board->parent->id, $post->board->parent->name) }} <span class="divider">/</span></li>
				@endif
				<li>{{ HTML::link('forum/board/view/'. $post->board->id, $post->board->name) }} <span class="divider">/</span></li>
				<li class="active">
					{{ $post->name }}					
					@if (count($replies) == 30 || isset($_GET['page']))
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
	</div>
</div>
<a name="replyField"></a>
{{ Form::open() }}
	{{ Form::hidden('report_resource_id', null, array('id' => 'report_resource_id')) }}
	{{ Form::hidden('report_resource_name', null, array('id' => 'report_resource_name')) }}
	<div id="reportToModerator" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
	    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    	<h3 id="myModalLabel">Report this post to a moderator</h3>
	  	</div>
	  	<div class="modal-body text-center">
	  		{{ Form::textarea('reason', null, array('placeholder' => 'Reason', 'class' => 'span5')) }}
	  	</div>
	  	<div class="modal-footer">
	  		{{ Form::submit('Submit Report', array('class' => 'btn btn-mini btn-primary')) }}
		    <button class="btn btn-mini btn-primary" data-dismiss="modal" aria-hidden="true" onClick="removeResources('report')">Close</button>
	  	</div>
	</div>
{{ Form::close() }}
@if ($gameMode)
	{{ Form::open() }}
		{{ Form::hidden('exp_resource_id', null, array('id' => 'exp_resource_id')) }}
		{{ Form::hidden('exp_resource_name', null, array('id' => 'exp_resource_name')) }}
		<div id="grantExp" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    	<h3 id="myModalLabel">Grant Experience to Player</h3>
		  	</div>
		  	<div class="modal-body text-center">
		  		<span id="exp_character_name"></span> currently has <span id="exp_character_exp"></span> experience
		  		{{ Form::text('exp', null, array('placeholder' => 'Experience Points', 'class' => 'span5')) }}
		  	</div>
		  	<div class="modal-footer">
		  		{{ Form::submit('Give Exp', array('class' => 'btn btn-mini btn-primary')) }}
			    <button class="btn btn-mini btn-primary" data-dismiss="modal" aria-hidden="true" onClick="removeResources('exp')">Close</button>
		  	</div>
		</div>
	{{ Form::close() }}
@endif
<script type="text/javascript">
	function addResourcetoReport(object,type) {
		var resourceId   = $(object).attr('data-resource-id');
		var resourceName = $(object).attr('data-resource-name');
		$('#'+ type +'_resource_id').val(resourceId);
		$('#'+ type +'_resource_name').val(resourceName);
	}
	function removeResources(type) {
		$('#'+ type +'_resource_id').val('');
		$('#'+ type +'_resource_name').val('');
	}
	function addQuote(object) {
		$('#collapseReply').addClass('in');
		var quoteId   = $(object).attr('data-quote-id');
		var quoteType = $(object).attr('data-quote-type');
		var quoteName = $(object).attr('data-quote-name');
		$('#quote_id').val(quoteId);
		$('#quote_type').val(quoteType);
		$('#quote').val('Quoting: '+quoteName);
	}
</script>