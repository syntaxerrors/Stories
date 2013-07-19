										@if ($reply->character_id != null && $reply->post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION)
											{{ HTML::link('character/sheet/'. $reply->character->id, $reply->character->name, array('class' => 'lead')) }}<br />
											<small>User: {{ HTML::link('profile/user/'. $reply->author->id, $reply->author->username) }}</small>
										@else
											{{ HTML::link('profile/user/'. $reply->author->id, $reply->author->username, array('class' => 'lead')) }}
										@endif
										<br />
										@if ($reply->character_id != null && $reply->post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION)
											@if ($reply->character->characterClass != null)
												<small>Class: {{ $reply->character->characterClass->gameClass->name }}</small>
												<br />
											@endif
											@if (file_exists(public_path() .'/img/forum/avatars/'. classify($reply->character->game->name) . '_'. classify($reply->character->name) .'.png'))
												{{ HTML::image(
													'img/forum/avatars/'. classify($reply->character->game->name) . '_'. classify($reply->character->name) .'.png',
													null,
													array('style' => 'width: 100px;', 'class' => 'img-polaroid')
												) }}
											@else
												{{ HTML::image($reply->author->gravitar, null, array('class'=> 'img-polaroid', 'style' => 'width: 100px;')) }}
											@endif
											<br />
											<small>
												Posts: {{ $reply->character->postsCount }}
										@else
											<small>{{ $reply->author->getHighestRole('Forum') }}</small>
											<br />
											{{ HTML::image($reply->author->gravitar, null, array('class'=> 'img-polaroid', 'style' => 'width: 100px;')) }}
											<br />
											<small>
												Posts: {{ $reply->author->postsCount }}
										@endif
											<br />
						                	{{ ($reply->author->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes'))
						                    	? HTML::image('img/icons/online.png', 'Online', array('title' => 'Online')) .' Online'
						                    	: HTML::image('img/icons/offline.png', 'Offline', array('title' => 'Offline')) .' Offline'
						                    ) }}
						                </small>