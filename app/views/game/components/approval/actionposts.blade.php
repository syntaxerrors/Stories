				<table class="table table-hover table-striped table-condensed text-center">
					<caption>Action Posts Awaiting Response</caption>
					<thead>
						<tr>
							<th style="width: 33%">Post / Roll</th>
							<th style="width: 33%">User</th>
							<th style="width: 33%">Actions</th>
						</tr>
					</thead>
						@foreach ($game->actionsAwaitingApproval as $reply)
							<tr>
								<td>
									{{ HTML::link('forum/post/view/'. $reply->post->keyName .'#reply:'. $reply->id, $reply->name, array('target' => '_blank')) }} / {{ $reply->roll->roll }}
								</td>
								<td>{{ HTML::link('profile/user/'. $reply->author->id, $reply->author->username) }}</td>
								<td>
									<div class="btn-group">
										{{ HTML::link('game/update/'. $reply->id .'/approvedFlag/1/reply', 'Approve', array('class' => 'btn btn-mini btn-primary')) }}
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>