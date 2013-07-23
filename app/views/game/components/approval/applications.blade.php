				<table class="table table-hover table-striped table-condensed text-center">
					<caption>Applications Awaiting Approval</caption>
					<thead>
						<tr>
							<th style="width: 33%">Name</th>
							<th style="width: 33%">User</th>
							<th style="width: 33%">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($game->charactersAwaitingApproval as $post)
							<tr>
								<td>
									{{ HTML::link('character/sheet/'. $post->character->id, $post->character->name, array('target' => '_blank')) }}
								</td>
								<td>{{ HTML::link('profile/user/'. $post->author->id, $post->author->username) }}</td>
								<td>
									<div class="btn-group">
										{{ HTML::link('forum/post/view/'. $post->keyName, 'View Post', array('target' => '_blank', 'class' => 'btn btn-mini btn-primary')) }}
										{{ HTML::link('game/update/'. $post->id .'/approvedFlag/1/post', 'Approve', array('class' => 'btn btn-mini btn-primary')) }}
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>