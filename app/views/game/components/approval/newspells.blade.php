				<table class="table table-hover table-striped table-condensed text-center">
					<caption>New Spells Awaiting Approval</caption>
					<thead>
						<tr>
							<th>Name</th>
							<th>Tree</th>
							<th>Level</th>
							<th>Use Cost</th>
							<th>Details</th>
							<th>Submitted By</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($game->unApprovedSpells as $spell)
							<tr>
								<td>{{ $spell->name }}</td>
								<td>{{ $spell->tree_name }}</td>
								<td>{{ $spell->level }}</td>
								<td>{{ $spell->useCost }}</td>
								<td>
									<div class="btn-group">
										<a href="javascript: void();" rel="popover" class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right" data-content="{{ nl2br($spell->stats) }}" data-html="true" title data-original-title="Stats">Stats</a>
										<a href="javascript: void();" rel="popover" class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right" data-content="{{ nl2br($spell->extra) }}" data-html="true" title data-original-title="Extra Details">Extra Details</a>
									</div>
								</td>
								<td>{{ HTML::link('character/sheet/'. $spell->character->id, $spell->character->name) }}</td>
								<td>
									<div class="btn-group">
										{{ HTML::link('game/update/'. $spell->id .'/approvedFlag/1/spell', 'Approve', array('class' => 'btn btn-mini btn-primary')) }}
										{{ HTML::link('game/denySpell/'. $spell->id, 'Deny', array('class' => 'btn btn-mini btn-danger')) }}
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>