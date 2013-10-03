							<!-- Start Author/Character Name -->
							@if ($gameMode && $post->character_id != null && $post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION)
								{{ HTML::link('character/sheet/'. $post->character->id, $post->character->name, array('class' => 'lead')) }}<br />
								<small>User: {{ HTML::link('profile/user/'. $post->author->id, $post->author->username) }}</small>
							@else
								{{ HTML::link('profile/user/'. $post->author->id, $post->author->username, array('class' => 'lead')) }}
							@endif
							<!-- End Author/Character Name -->
							<br />
							<!-- Start Avatar and Post Count -->
							@if ($gameMode && $post->character_id != null && $post->board->forum_board_type_id != Forum_Board::TYPE_APPLICATION)
								@if ($post->character->characterClass != null)
									<small>Class: {{ $post->character->characterClass->gameClass->name }}</small>
									<br />
								@endif
								@if (file_exists(public_path() .'/img/forum/avatars/'. classify($post->character->game->name) . '_'. classify($post->character->name) .'.png'))
									{{ HTML::image(
										'img/forum/avatars/'. classify($post->character->game->name) . '_'. classify($post->character->name) .'.png',
										null,
										array('style' => 'width: 100px;', 'class' => 'img-polaroid')
									) }}
								@else
									{{ HTML::image('img/no_user.png', null, array('class'=> 'img-polaroid', 'style' => 'width: 100px;')) }}
								@endif
								<br />
								<small>
									Posts: {{ $post->character->postsCount }}
							@else
								<small>{{ $post->author->getHighestRole('Forum') }}</small>
								<br />
								{{ HTML::image($post->author->gravitar, null, array('class'=> 'img-polaroid', 'style' => 'width: 100px;')) }}
								<br />
								<small>
									Posts: {{ $post->author->postsCount }}
							@endif
							<!-- End Avatar and Post Count -->
							<!-- Start Online Status -->
								<br />
								{{ ($post->author->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes'))
									? HTML::image('img/icons/online.png', 'Online', array('title' => 'Online')) .' Online'
									: HTML::image('img/icons/offline.png', 'Offline', array('title' => 'Offline')) .' Offline'
								) }}
							</small>
							<!-- End Online Status -->