<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Escalated Posts</div>
			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Title</th>
						<th>Author</th>
						<th>Reason</th>
						<th>Reported On</th>
						<th class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($escalatedPosts as $escalatedPost)
						<?php
							$modalId = 'modal_'. $escalatedPost->id;
							$modalHeader = $escalatedPost->resource->name .' <small>by '. $escalatedPost->resource->author->username .'</small>';
						?>
						<tr>
							<td class="text-center">
								<a class="accordion-toggle" data-toggle="collapse" href="#report_{{ $escalatedPost->id }}" style="text-decoration: none;" onClick="$(this).children().toggleClass('icon-caret-down').toggleClass('icon-caret-right');$('#report_{{ $escalatedPost->id }}_row').toggle();">
									<i class="icon-caret-right icon-large"></i>
								</a>
							</td>
							<td>
								<a href="#{{ $modalId }}" role="button" data-toggle="modal">{{ $escalatedPost->resource->name }}</a>
								@include('helpers.modalHeader', array('modalId' => $modalId, 'modalHeader' => $modalHeader))
								{{ $escalatedPost->resource->content }}
								@include('helpers.modalFooter')
							</td>
							<td>{{ HTML::link('/profile/'. $escalatedPost->resource->author->id, $escalatedPost->resource->author->username, array('target' => '_blank')) }}</td>
							<td>{{ $escalatedPost->reason }}</td>
							<td>{{ $escalatedPost->created_at }}</td>
							<td class="text-right">
								<div class="btn-group">
									{{ HTML::link('forum/moderation/remove-report/'. $escalatedPost->id, 'Remove Report', array('class' => 'confirm-continue btn btn-mini btn-primary')) }}
									{{ HTML::link('forum/admin/delete-post/'. $escalatedPost->id, 'Delete Post', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
								</div>
							</td>
						</tr>
						<tr id="report_{{ $escalatedPost->id }}_row" style="display: none;">
							<td>&nbsp;</td>
							<td colspan="5">
								<div id="report_{{ $escalatedPost->id }}" class="accordion-body collapse">
									@if (count($escalatedPost->history) > 0)
										@foreach ($escalatedPost->history as $history)
											<small>
												@if ($history instanceof Forum_Moderation_Reply)
													UPDATE:
												@elseif ($history instanceof Forum_Moderation_Log)
													ACTION:
												@endif
												{{ HTML::link('/profile/'. $history->user->id, $history->user->username, array('target' => '_blank')) }} on {{ $history->created_at }}
											</small>
											<br />
											@if ($history instanceof Forum_Moderation_Reply)
												{{ $history->content }}
											@elseif ($history instanceof Forum_Moderation_Log)
												{{ $history->action }}
											@endif
											<hr />
										@endforeach
									@else
										No actions for this report.
									@endif
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>