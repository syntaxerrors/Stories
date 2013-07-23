				<table class="table table-hover table-striped table-condensed text-center">
					<thead>
						<tr>
							<th>Name</th>
							<th>Class</th>
							<th>EXP</th>
							<th>User</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($game->allCharacters as $character)
							@if ($character->activeFlag == 0 || ($character->hitPoints == 0 && $game->hitPointsName != null))
								<?php continue; ?>
							@endif
							<tr>
								<td>
									{{ HTML::link('character/sheet/'. $character->id, $character->name, array('target' => '_blank')) }}
									@if ($character->hitPoints == 0 && $game->hitPointsName != null)
										<small class="text-error">(Desceased)</small>
									@endif
								</td>
								<td>
									{{ ($character->characterClass != null ? $character->characterClass->gameClass->name : 'Unknown') }}
									@if ($character->level != 0)
										({{ $character->level }})
									@endif
								</td>
								<td>{{ $character->experience }}</td>
								<td>{{ HTML::link('profile/user/'. $character->user->id, $character->user->username) }}</td>
								<td>
									<div class="btn-group">
										<a href="#grantExp"
											onClick="$('#exp_character_id').val('{{ $character->id }}');$('#exp_character_name').text('{{ $character->name }}');$('#exp_character_exp').text('{{ $character->experience }}');"
											role="button"
											data-toggle="modal"
											class="btn btn-mini btn-primary"
											style="font-size: 14px;"
											title="Add Experience">
												<i class="icon-plus"></i>
										</a>
										<a href="#modal" role="button" class="btn btn-mini btn-primary" data-toggle="modal" data-remote="/game/character/getExpHistory/{{ $character->id }}">
											<i class="icon-book" title="Experience History"></i>
										</a>
										{{ HTML::linkIcon(
											'game/update/'. $character->id .'/activeFlag/'. ($character->activeFlag == 1 ? 0 : 1),
											($character->activeFlag == 1 ? 'icon-ok' : 'icon-remove'),
											null,
											array('class' => 'btn btn-mini btn-primary', 'title' => ($character->activeFlag == 1 ? 'Make Inactive' : 'Make Active'), 'style' => 'font-size: 14px;')
										) }}
										{{ HTML::linkIcon(
											'game/update/'. $character->id .'/npcFlag/'. ($character->npcFlag == 1 ? 0 : 1),
											($character->npcFlag == 1 ? 'icon-circle-arrow-up' : 'icon-circle-arrow-down'),
											null,
											array('class' => 'btn btn-mini btn-primary', 'title' => ($character->npcFlag == 1 ? 'Make Player' : 'Make NPC'), 'style' => 'font-size: 14px;')
										) }}
										{{ HTML::linkIcon(
											'game/update/'. $character->id .'/creatureFlag/'. ($character->creatureFlag == 1 ? 0 : 1),
											($character->creatureFlag == 1 ? 'icon-user' : 'icon-magic'),
											null,
											array('class' => 'btn btn-mini btn-primary', 'title' => ($character->creatureFlag == 1 ? 'Make Character' : 'Make Creature'), 'style' => 'font-size: 14px;')
										) }}
										{{ HTML::linkIcon(
											'game/character/edit/'. $character->id,
											'icon-edit',
											null,
											array('class' => 'btn btn-mini btn-primary', 'title' => 'Edit', 'style' => 'font-size: 14px;')
										) }}
										<a href="#modal" role="button" class="btn btn-mini btn-primary" data-toggle="modal" data-remote="/game/character/getNotes/{{ $character->id }}">
											<i class="icon-tags" title="Notes"></i> 
											<?php
												$notes = count($character->notes);
											?>
											@if ($notes > 0)
												({{ $notes }})
											@endif
										</a>
										{{ HTML::link('game/character/delete/'. $character->id, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
									</div>
								</td>
						@endforeach
					</tbody>
				</table>