<div class="row-fluid">
	<div class="span11">
		<!-- Start Breadcrumbs -->
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
		<!-- End Breadcrumbs -->
		<!-- Start Header Details -->
		@if ($replies->count() > 30)
			<div>
				<div style="vertical-align: top;display: inline-block;">
					<span class="muted">Page:</span>&nbsp;
				</div>
				<div style="display: inline-block;">{{ $replies->links(3, 'pagination-mini', false) }}</div>
			</div>
		@endif
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
		<!-- End Header Details -->
		<!--Start Post -->
		@if (!isset($_GET['page']) || $_GET['page'] == 1)
			@include('forum.post.components.postdisplay')
		@endif
		<!--End Post -->
		<!-- Start Replies -->
		@if (count($replies) > 0)
			@foreach ($replies as $reply)
				@include('forum.post.components.postdisplay', array('post' => $reply))
			@endforeach
			@if ($replies->count() > 30)
				<div>
					<div style="vertical-align: top;display: inline-block;">
						<span class="muted">Page:</span>&nbsp;
					</div>
					<div style="display: inline-block;">{{ $replies->links(3, 'pagination-mini', false) }}</div>
				</div>
			@endif
			<br />
		@endif
		<!-- End Replies -->
		<!-- Start Quick Reply -->
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
							@include('forum.post.components.quickreply')
							<div class="controls text-center">
								{{ Form::submit('Post', array('class' => 'btn btn-small btn-primary span3', 'tabindex' => 3)) }}
							</div>
						@endif
					</div>
				</div>
			{{ Form::close() }}
		@endif
		<!-- End Quick Reply -->
		<!-- Start Bottom Breadcrumbs -->
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
		<!-- End Bottom Breadcrumbs -->
	</div>
</div>
<a name="replyField"></a>
<!-- Start Report to moderator modal -->
{{ Form::open() }}
	{{ Form::hidden('report_resource_id', null, array('id' => 'report_resource_id')) }}
	{{ Form::hidden('report_resource_name', null, array('id' => 'report_resource_name')) }}
	@include('helpers.modalHeader', array('modalId' => 'reportToModerator', 'modalHeader' => 'Report this post to a moderator'))
		{{ Form::textarea('reason', null, array('placeholder' => 'Reason', 'class' => 'span5')) }}
		</div>
		<div class="modal-footer">
			{{ Form::submit('Submit Report', array('class' => 'btn btn-mini btn-primary')) }}
			<button class="btn btn-mini btn-primary" data-dismiss="modal" aria-hidden="true" onClick="removeResources('report')">Close</button>
		</div>
	</div>
{{ Form::close() }}
<!-- End Report to moderator modal -->
<!-- Start Grant Experience modal -->
@if ($gameMode)
	{{ Form::open() }}
		{{ Form::hidden('exp_resource_id', null, array('id' => 'exp_resource_id')) }}
		{{ Form::hidden('exp_resource_name', null, array('id' => 'exp_resource_name')) }}
		@include('helpers.modalHeader', array('modalId' => 'grantExp', 'modalHeader' => 'Grant Experience to Player'))
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
<!-- End Grant Experience modal -->
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