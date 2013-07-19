							@if (count($attachments) > 0)
								@if ($post->board->forum_board_type_id == Forum\Board::TYPE_APPLICATION && $activeUser->id != $post->author->id && !$post->board->category->game->isStoryteller($activeUser->id))
									<small class="text-info">No attachments for this post.</small>
								@else
									@foreach ($attachments as $attachment)
										<span style="width: 30px;" class="text-center"><i class="icon-file"></i></span>&nbsp;
										{{ HTML::link(
											str_replace(public_path(), '', $attachment),
											str_replace(public_path() .'/img/forum/attachments/'. $post->keyName .'/', '', $attachment),
											array('target' => '_blank')) }}&nbsp;
										@if ($post->author->id == $activeUser->id || $activeUser->can('SV_ADMIN'))
											{{ HTML::linkIcon('forum/post/delete/'. $post->keyName .'/attachment/'. str_replace('/', '|', $attachment), 'icon-remove', null, array('class' => 'act-danger confirm-remove ')) }}
										@endif
										<br />
									@endforeach
									<br />
								@endif
							@else
								<small class="text-info">No attachments for this post.</small>
								<br />
							@endif
							@if ($post->author->id == $activeUser->id)
								{{ Form::open(array('files' => true)) }}
									<div class="fileupload fileupload-new" data-provides="fileupload" data-name="image">
										<div class="input-append">
											<div class="uneditable-input span3">
												<i class="icon-file fileupload-exists"></i>&nbsp;
												<span class="fileupload-preview"></span>
											</div>
											<span class="btn btn-file btn-primary">
												<span class="fileupload-new">Select file</span>
												<span class="fileupload-exists">Change</span>
												<input type="file" />
											</span>
											{{ Form::submit('Upload', array('class' => 'btn fileupload-exists btn-primary')) }}
											<a href="javascript: void();" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">Remove</a>
										</div>
									</div>
								{{ Form::close(); }}
							@endif