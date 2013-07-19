					@if ($post->character_id != null)
						<?php $class = ($post->character->characterClass != null ? $post->character->characterClass->gameClass->name : null); ?>
						<div class="span9">
							@if ($activeUser->id == 1 || $post->character->user_id == $activeUser->id || in_array($activeUser->id, $storyTellerIds))
				    			<table class="table table-hover table-condensed">
				    				<caption>Character Details</caption>
				    				<tbody>
				    					<tr>
				    						<td style="width: 100px;"><strong>Name:</strong></td>
				    						<td>{{ $post->character->name }}</td>
				    					</tr>
				    					@if ($class != null)
					    					<tr>
					    						<td><strong>Class:</strong></td>
					    						<td>{{ $class }}</td>
					    					</tr>
					    				@endif
				    					<tr>
				    						<td><strong>Experience</strong></td>
				    						<td>{{ $post->character->experience }}</td>
				    					</tr>
				    				</tbody>
				    			</table>
								@if (count($post->board->category->game->template->appearances) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#appearance_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Details <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="appearance_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->appearances as $appearance)
													@if ($post->character->getValue('Appearance', $appearance->id) != null)
														<tr>
															<td>{{ $appearance->name }}</td>
															<td>{{ nl2br($post->character->getValue('Appearance', $appearance->id)) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->stats) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#stats_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Stats <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="stats_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->stats as $stat)
													@if ($post->character->getValue('BaseStat', $stat->id) != null)
														<tr>
															<td>{{ $stat->name }}</td>
															<td>{{ $post->character->getValue('BaseStat', $stat->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->gameAttributes) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#attributes_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Attributes <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="attributes_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->gameAttributes as $attribute)
													@if ($post->character->getValue('Attribute', $attribute->id) != null)
														<tr>
															<td>{{ $attribute->name }}</td>
															<td>{{ $post->character->getValue('AttributeMod', $attribute->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->secondaryAttributes) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#secondary_attributes_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Secondary Attributes <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="secondary_attributes_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->secondaryAttributes as $attribute)
													@if ($post->character->getValue('SecondaryAttribute', $attribute->id) != null)
														<tr>
															<td>{{ $attribute->name }}</td>
															<td>{{ $attribute->gameAttribute->name }}</td>
															<td>{{ $post->character->getValue('SecondaryAttribute', $attribute->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->skills) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#skills_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Skills <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="skills_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->skills as $skill)
													@if ($post->character->getValue('Skill', $skill->id) != 0)
														<tr>
															<td>{{ $skill->name }}</td>
															<td>{{ $skill->gameAttribute->name }}</td>
															<td>{{ $post->character->getValue('Skill', $skill->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->traits) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#advantages_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Advantages <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="advantages_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->traits as $trait)
													@if ($trait->advantageFlag == 1)
														@if ($post->character->getValue('Trait', $trait->id) != 0)
															<tr>
																<td>{{ $trait->name }}</td>
																<td>{{ $post->character->getValue('Trait', $trait->id) }}</td>
															</tr>
														@endif
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
									<a class="accordion-toggle" data-toggle="collapse" href="#disadvantages_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Disadvantages <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="disadvantages_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->traits as $trait)
													@if ($trait->advantageFlag == 0)
														@if ($post->character->getValue('Trait', $trait->id) != 0)
															<tr>
																<td>{{ $trait->name }}</td>
																<td>{{ $post->character->getValue('Trait', $trait->id) }}</td>
															</tr>
														@endif
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->inventory) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#inventory_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Inventory <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="inventory_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->inventory as $inventory)
													@if ($post->character->getValue('Inventory', $inventory->id) != null)
														<tr>
															<td>{{ $inventory->name }}</td>
															<td>{{ $post->character->getValue('Inventory', $inventory->id) }}</td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
								@if (count($post->board->category->game->template->currency) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#currency_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Currency <i class="icon-chevron-down"></i>
									</a>
									<hr />
									<div id="currency_{{ $post->id }}" class="accordion-body collapse">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->currency as $currency)
													@if ($post->character->getValue('Currency', $currency->id) != null)
														<tr>
															<td>{{ $currency->name }}</td>
															<td>{{ $post->character->getValue('Currency', $currency->id) }}</td>
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
				    						<td>{{ $post->character->name }}</td>
				    					</tr>
				    					@if ($class != null)
					    					<tr>
					    						<td><strong>Class:</strong></td>
					    						<td>{{ $class }}</td>
					    					</tr>
					    				@endif
				    				</tbody>
				    			</table>
								@if (count($post->board->category->game->template->appearances) > 0)
									<a class="accordion-toggle" data-toggle="collapse" href="#appearance_{{ $post->id }}" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
										Details <i class="icon-chevron-up"></i>
									</a>
									<hr />
									<div id="appearance_{{ $post->id }}" class="accordion-body collapse in">
										<table class="table table-hover table-condensed">
											<tbody>
												@foreach ($post->board->category->game->template->appearances as $appearance)
													@if ($post->character->getValue('Appearance', $appearance->id) != null)
														<tr>
															<td>{{ $appearance->name }}</td>
															<td>{{ $post->character->getValue('Appearance', $appearance->id) }}</td>
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