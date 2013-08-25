								@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_SUPPORT && isset($post->forum_post_type_id) && $post->forum_post_type_id != Forum_Post::TYPE_ANNOUNCEMENT)
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
										@if ($post->adminReviewFlag == 1 || $post->moderatorLockedFlag == 1)
											<span class="text-error">Under review <i class="icon-legal" style="width: 15px;"></i></span>
										@else
											<a href="#reportToModerator" onClick="addResourcetoReport(this, 'report')" data-resource-id="{{ $post->id }}" data-resource-name="{{ $post->forumType }}" role="button" data-toggle="modal">
												Report to Moderator <i class="icon-legal" style="width: 15px;"></i>
											</a>
										@endif
										@if ($gameMode)
											@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->board->category->game->isStoryteller($activeUser->id) && $post->character != null)
												<br />
												<a href="#grantExp" onClick="addResourcetoReport(this, 'exp');$('#exp_character_name').text('{{ $post->character->name }}');$('#exp_character_exp').text('{{ $post->character->experience }}');" data-resource-id="{{ $post->id }}" data-resource-name="post" role="button" data-toggle="modal">
													Grant Experience <i class="icon-plus-sign" style="width: 15px;"></i>
												</a>
											@endif
										@endif
									</small>
								</div>
								<br />
								<small><small>On {{ $post->created_at }}</small></small>
								<hr />
								@if ($post->quote != null)
									<small>
										{{ HTML::link('forum/post/view/'. $post->quote->post->keyName .'#reply:'. $post->quote->keyName, 'Quote from: '. $post->quote->displayName .' on '. $post->quote->created_at) }}
									</small><br />
										<?php $newQuote = $post->quote; ?>
										@while ($newQuote != null)
										<blockquote>
											@if ($newQuote->forum_reply_type_id == Forum_Reply::TYPE_ACTION)
												@if ($newQuote->roll->roll != 9999)
													{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
													@if ($newQuote->roll->roll == 42 || $newQuote->roll->roll == 69)
														<span class="text-warning">
													@else
														<span>
													@endif
														{{ $newQuote->displayName }} rolled a {{ $newQuote->roll->roll }}
													</span>
												@else
													<span class="text-warning">Story Action</span>
												@endif
												<br />
												<br />
											@endif
											<i class="icon-quote-left"></i> {{ BBCode::parse(e($newQuote->content)) }}
											<?php $newQuote = $newQuote->quote; ?>
									</blockquote>
										@endwhile
									<hr />
								@endif
								@if ($post->forumType == 'reply')
									@if ($post->forum_reply_type_id == Forum_Reply::TYPE_ACTION)
										@if ($post->roll->roll != 9999)
											{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
											@if ($post->roll->roll == 42 || $post->roll->roll == 69)
												<span class="text-warning">
											@else
												<span>
											@endif
												{{ $post->displayName }} rolled a {{ $post->roll->roll }}
											</span>
										@else
											<span class="text-warning">Story Action</span>
										@endif
										<br />
										<br />
										{{ BBCode::parse(e($post->content)) }}
									@elseif ($post->forum_reply_type_id == Forum_Reply::TYPE_INNER_THOUGHT)
										<span class="text-info">
											{{ $post->icon }} This is an inner-thought post.  Anything detailed here is only known to the character and is used for role-playing purposes. {{ $post->icon }}
										</span>
										<br />
										<br />
										{{ BBCode::parse(e($post->content)) }}
									@elseif ($post->forum_reply_type_id == Forum_Reply::TYPE_CONVERSATION)
										<i class="icon-quote-left" style="padding-right: 5px;"></i>
										{{ str_replace('<br /><br />', '<br /><br /><i class="icon-quote-left" style="padding-right: 5px;"></i>', BBCode::parse(e($post->content))) }}
									@else
										{{ BBCode::parse(e($post->content)) }}
									@endif
								@else
									@if ($gameMode)
										@if ($post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->forum_post_type_id == Forum_Post::TYPE_APPLICATION)
											{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
											@foreach ($post->character->rolls as $roll)
												{{ $roll->roll }}&nbsp;&nbsp;
											@endforeach
											<hr />
										@endif
									@endif
									@if ($post->adminReviewFlag == 0)
										{{ BBCode::parse(e($post->content)) }}
									@else
										<span class="text-error" style="font-weight: bold; font-size: 1.1em;">This post is under admin review.  It may be restored or deleted in the near future.</span>
									@endif
								@endif