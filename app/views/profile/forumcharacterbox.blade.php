<div class="rowspan">
	<div class="span8">
		<div class="media">
			{{ $character->avatar }}
			<div class="media-body">
				<h4 class="media-heading">
					{{ HTML::link('character/sheet/'. $character->id, $character->name) }}
					@if ($user->id == $activeUser->id || $character->game->isStoryteller($activeUser->id))
						<div class="pull-right">
							@if (count($character->game->template->magicTrees) > 0)
								{{ HTML::link('character/spellbook/'. $character->id, 'Spellbook') }}&nbsp;|&nbsp;
							@endif
							@if ($user->id == $activeUser->id)
								{{ HTML::link('character/update/'. $character->id, 'Edit') }}
							@endif
						</div>
					@endif
				</h4>
				<?php $class = ($character->characterClass != null ? $character->characterClass->gameClass->name : null);  ?>
				<table class="table table-hover table-condensed">
					<tbody>
						<tr>
							<td style="font-weight: bold; width: 130px;">Game:</td>
							<td>{{ $character->game->name }}</td>
						</tr>
						@if ($class != null)
							<tr>
								<td style="font-weight: bold">Class:</td>
								<td>{{ $class }}</td>
							</tr>
						@endif
						@foreach ($character->game->template->appearances as $appearance)
							@if ($character->getValue('Appearance', $appearance->id) != null)
								<tr>
									<td style="font-weight: bold">{{ ucwords($appearance->name) }}:</td>
									<td>{{ nl2br($character->getValue('Appearance', $appearance->id)) }}</td>
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>
<hr />
<br />