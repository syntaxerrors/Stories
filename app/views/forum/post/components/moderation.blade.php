							<div class="btn-group">
								@if ($post->adminReviewFlag == 1 && !$activeUser->checkPermission('FORUM_ADMIN'))
									<a href="javascript: void(0);" class="btn btn-primary btn-mini disabled">Remove Report</a>
								@else
									{{ HTML::link('forum/moderation/remove-report/'. $post->moderations->first()->id, 'Remove Report', array('class' => 'btn btn-primary btn-mini')) }}
								@endif
								@if ($post->adminReviewFlag == 1)
									<a href="javascript: void(0);" class="btn btn-warning btn-mini disabled">Admin Review</a>
									@if ($activeUser->checkPermission('FORUM_ADMIN'))
										{{ HTML::link('forum/admin/delete-post/'. $post->moderations->first()->id, 'Delete Post', array('class' => 'confirm-remove btn btn-danger btn-mini')) }}
									@endif
								@else
									{{ HTML::link('forum/moderation/admin-review/'. $post->moderations->first()->id, 'Admin Review', array('class' => 'confirm-continue btn btn-warning btn-mini')) }}
								@endif
							</div>
							<hr />
							@if (count($post->moderations->history) > 0 && $post->moderations->history[0] != null)
								@foreach ($post->moderations->history as $history)
									<small>
										@if ($history instanceof Forum_Moderation_Reply)
											UPDATE:
										@elseif ($history instanceof Forum_Moderation_Log)
											ACTION:
										@endif
										{{ HTML::link('/profile/'. $history->user->id, $history->user->username, array('target' => '_blank')) }} on {{ $history->created_at }} - 
										@if ($history instanceof Forum_Moderation_Reply)
											{{ $history->content }}
										@elseif ($history instanceof Forum_Moderation_Log)
											{{ $history->action }}
										@endif
									</small>
									<hr />
								@endforeach
							@else
								No actions for this report.
							@endif