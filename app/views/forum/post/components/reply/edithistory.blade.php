								@if (count($reply->history) > 0)
									@foreach ($reply->history as $edit)
										<small class="text-info">Edited by {{ $edit->user->username }} on {{ $edit->created_at }}: {{ $edit->reason }}</small><br />
									@endforeach
								@else
									<small class="text-info">No edits for this post.</small>
								@endif