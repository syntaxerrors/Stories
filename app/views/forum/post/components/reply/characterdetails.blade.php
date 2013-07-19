					@if ($reply->character_id != null)
						<?php $class = ($reply->character->characterClass != null ? $reply->character->characterClass->gameClass->name : null); ?>
						<div class="span9">
							@if ($reply->character->user_id == $activeUser->id || $post->board->category->game->isStoryteller($activeUser->id))
				    			<table class="table table-hover table-condensed">
				    				<caption>Character Details</caption>
				    				<tbody>
				    					<tr>
				    						<td style="width: 100px;"><strong>Name:</strong></td>
				    						<td>{{ $reply->character->name }}</td>
				    					</tr>
				    					@if ($class != null)
					    					<tr>
					    						<td><strong>Class:</strong></td>
					    						<td>{{ $class }}</td>
					    					</tr>
										@endif
				    					<tr>
				    						<td><strong>Experience</strong></td>
				    						<td>{{ $reply->character->experience }}</td>
				    					</tr>
				    				</tbody>
				    			</table>
								@if (count($reply->post->board->category->game->appearances) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#appearance_{{ $reply->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Details <i class="icon-chevron-up"></i>
									</a>
									<hr />
									<div id="appearance_{{ $reply->id }}" class="accordion-body collapse in">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($reply->post->board->category->game->appearances as $appearance)
													@if ($reply->character->getValue('Appearance', $appearance->id) != null)
														<tr>
															<td>{{ $appearance->name }}</td>
															<td>{{ $reply->character->getValue('Appearance', $appearance->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($reply->post->board->category->game->stats) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#stats_{{ $reply->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Stats <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="stats_{{ $reply->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($reply->post->board->category->game->stats as $stat)
													@if ($reply->character->getValue('BaseStat', $stat->id) != null)
														<tr>
															<td>{{ $stat->name }}</td>
															<td>{{ $reply->character->getValue('BaseStat', $stat->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($reply->post->board->category->game->gameAttributes) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#attributes_{{ $reply->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Attributes <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="attributes_{{ $reply->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($reply->post->board->category->game->gameAttributes as $attribute)
													@if ($reply->character->getValue('Attribute', $attribute->id) != null)
														<tr>
															<td>{{ $attribute->name }}</td>
															<td>{{ $reply->character->getValue('Attribute', $attribute->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($reply->post->board->category->game->secondaryAttributes) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#secondary_attributes_{{ $reply->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Secondary Attributes <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="secondary_attributes_{{ $reply->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($reply->post->board->category->game->secondaryAttributes as $attribute)
													@if ($reply->character->getValue('SecondaryAttribute', $attribute->id) != null)
														<tr>
															<td>{{ $attribute->name }}</td>
															<td>{{ $attribute->gameAttribute->name }}</td>
															<td>{{ $reply->character->getValue('SecondaryAttribute', $attribute->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($reply->post->board->category->game->skills) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#skills_{{ $reply->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Skills <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="skills_{{ $reply->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($reply->post->board->category->game->skills as $skill)
													@if ($reply->character->getValue('Skill', $skill->id) != null)
														<tr>
															<td>{{ $skill->name }}</td>
															<td>{{ $skill->gameAttribute->name }}</td>
															<td>{{ $reply->character->getValue('Skill', $skill->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
							@else
				    			<table class="table table-hover table-condensed">
				    				<caption>Character Details</caption>
				    				<tbody>
				    					<tr>
				    						<td style="width: 100px;"><strong>Name:</strong></td>
				    						<td>{{ $reply->character->name }}</td>
				    					</tr>
				    					@if ($class != null)
					    					<tr>
					    						<td><strong>Class:</strong></td>
					    						<td>{{ $class }}</td>
					    					</tr>
										@endif
				    				</tbody>
				    			</table>
								@if (count($reply->post->board->category->game->appearances) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#appearance_{{ $reply->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Details <i class="icon-chevron-up"></i>
									</a>
									<hr />
									<div id="appearance_{{ $reply->id }}" class="accordion-body collapse in">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($reply->post->board->category->game->appearances as $appearance)
													@if ($reply->character->getValue('Appearance', $appearance->id) != null)
														<tr>
															<td>{{ $appearance->name }}</td>
															<td>{{ $reply->character->getValue('Appearance', $appearance->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
			    			@endif
						</div>
					@endif