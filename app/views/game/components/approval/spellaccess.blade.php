				<table class="table table-hover table-striped table-condensed text-center">
					<caption>Character Spell Access Awaiting Approval</caption>
					<thead>
						<tr>
							<th>Spell</th>
							<th>Character</th>
							<th>Details</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($game->unApprovedCharacterSpells as $spell)
							<tr>
								<td>{{ $spell->gameSpell->name }}</td>
								<td>{{ HTML::link('character/sheet/'. $spell->character->id, $spell->character->name, array('target' => '_blank')) }}</td>
								<td>
									<div class="btn-group">
										<a href="javascript: void();" rel="popover" class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right" data-content="{{ nl2br($spell->description) }}" data-html="true" title data-original-title="Description">Description</a>
										<a href="javascript: void();" rel="popover" class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right" data-content="{{ nl2br($spell->buyCost) }}" data-html="true" title data-original-title="Buy Cost">Buy Cost</a>
									</div>
								</td>
								<td>
									<div class="btn-group">
										{{ HTML::link('game/update/'. $spell->id .'/approvedFlag/1/characterSpell', 'Approve', array('class' => 'btn btn-mini btn-primary')) }}
										{{ HTML::link('game/denyCharacterSpell/'. $spell->id, 'Deny', array('class' => 'btn btn-mini btn-danger')) }}
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>