								@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && $post->forum_post_type_id != Forum_Post::TYPE_ANNOUNCEMENT)
									{{ $post->status->icon }}
								@else
									{{ $post->icon }}
								@endif
								<strong>{{ HTML::link('forum/post/view/'. $post->keyName .'#reply:'. $post->id, $post->name, array('name' => 'reply:'. $post->id, 'rel' => 'nofollow')) }}</strong>
								@if ($post->forum_post_type_id == Forum_Post::TYPE_APPLICATION && $post->approvedFlag == 0)
									<small class="label label-important">Unapproved</small>
								@endif
								<div class="pull-right text-bottom text-right">
									<small>
										@if ($post->frontPageFlag == 1)
											<small class="label pull-right">Front Page Post</small>
											<div class="clearfix"></div>
										@endif
										<a href="#reportToModerator" onClick="addResourcetoReport(this, 'report')" data-resource-id="{{ $post->id }}" data-resource-name="post" role="button" data-toggle="modal">
											Report to Moderator <i class="icon-legal" style="width: 15px;"></i>
										</a>
										@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->board->category->game->isStoryteller($activeUser->id) && $post->character != null)
											<br />
											<a href="#grantExp" onClick="addResourcetoReport(this, 'exp');$('#exp_character_name').text('{{ $post->character->name }}');$('#exp_character_exp').text('{{ $post->character->experience }}');" data-resource-id="{{ $post->id }}" data-resource-name="post" role="button" data-toggle="modal">
												Grant Experience <i class="icon-plus-sign" style="width: 15px;"></i>
											</a>
										@endif
										@if ($post->moderationCount > 0)
											<br />
											<span class="text-error">Reported {{ $post->moderationCount .' '. Str::plural('time', $post->moderationCount) }}</span>
										@endif
									</small>
								</div>
								<br />
								<small><small>On {{ $post->created_at }}</small></small>
								<hr />
								@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->forum_post_type_id == Forum_Post::TYPE_APPLICATION)
									{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
									@foreach ($post->character->rolls as $roll)
										{{ $roll->roll }}&nbsp;&nbsp;
									@endforeach
									<hr />
								@endif
								{{ BBCode::parse(e($post->content)) }}