							@if (count($post->history) > 0)
								@foreach ($post->history as $edit)
									<small class="text-info">Edited by {{ $edit->user->username }} on {{ $edit->created_at }}: {{ $edit->reason }}</small><br />
								@endforeach
							@else
								<small class="text-info">No edits for this post.</small>
							@endif