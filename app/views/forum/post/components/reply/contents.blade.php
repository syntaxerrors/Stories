										{{ $reply->icon }}
										<strong>{{ HTML::link('forum/post/view/'. $post->keyName .'#reply:'. $reply->id, $reply->name, array('name' => 'reply:'. $reply->id, 'rel' => 'nofollow')) }}</strong>
										@if ($reply->forum_reply_type_id == Forum_Reply::TYPE_ACTION && $reply->approvedFlag == 0 && $reply->post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME)
											<small class="label label-important">Unapproved</small>
										@endif
										<div class="pull-right text-bottom text-right">
											<small>
												<a href="#reportToModerator" onClick="addResourcetoReport(this)" data-resource-id="{{ $reply->id }}" data-resource-name="reply" role="button" data-toggle="modal">
													Report to Moderator <i class="icon-legal"></i>
												</a>
												@if ($reply->post->board->category->forum_category_type_id == Forum_Category::TYPE_GAME && $post->board->category->game->isStoryteller($activeUser->id) &&  $reply->character != null)
													<br />
													<a href="#grantExp" onClick="addResourcetoReport(this, 'exp');$('#exp_character_name').text('{{ $reply->character->name }}');$('#exp_character_exp').text('{{ $reply->character->experience }}');" data-resource-id="{{ $reply->id }}" data-resource-name="reply" role="button" data-toggle="modal">
														Grant Experience <i class="icon-plus-sign" style="width: 15px;"></i>
													</a>
												@endif
												@if ($reply->moderationCount > 0)
													<br />
													<span class="text-error">Reported {{ $reply->moderationCount .' '. Str::plural('time', $reply->moderationCount) }}</span>
												@endif
											</small>
										</div>
										<br />
										<small><small>On {{ $reply->created_at }}</small></small>
										<hr />
										@if ($reply->quote != null)
											<small>
												{{ HTML::link('forum/post/view/'. $reply->quote->post->keyName .'#reply:'. $reply->quote->keyName, 'Quote from: '. $reply->quote->displayName .' on '. $reply->quote->created_at) }}
											</small><br />
												<?php $newQuote = $reply->quote; ?>
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
										@if ($reply->forum_reply_type_id == Forum_Reply::TYPE_ACTION)
											@if ($reply->roll->roll != 9999)
												{{ HTML::image('img/dice_white.png', null, array('style' => 'width: 18px;position: relative; bottom: 2px;')) }}
												@if ($reply->roll->roll == 42 || $reply->roll->roll == 69)
													<span class="text-warning">
												@else
													<span>
												@endif
													{{ $reply->displayName }} rolled a {{ $reply->roll->roll }}
												</span>
											@else
												<span class="text-warning">Story Action</span>
											@endif
											<br />
											<br />
											{{ BBCode::parse(e($reply->content)) }}
										@elseif ($reply->forum_reply_type_id == Forum_Reply::TYPE_INNER_THOUGHT)
											<span class="text-info">
												{{ $reply->icon }} This is an inner-thought post.  Anything detailed here is only known to the character and is used for role-playing purposes. {{ $reply->icon }}
											</span>
											<br />
											<br />
											{{ BBCode::parse(e($reply->content)) }}
										@elseif ($reply->forum_reply_type_id == Forum_Reply::TYPE_CONVERSATION)
											<i class="icon-quote-left" style="padding-right: 5px;"></i>
											{{ str_replace('<br /><br />', '<br /><br /><i class="icon-quote-left" style="padding-right: 5px;"></i>', BBCode::parse(e($reply->content))) }}
										@else
											{{ BBCode::parse(e($reply->content)) }}
										@endif