<div class="row-fluid">
	<div class="offset3 span1" style="height: 260px;">
		<div style="background: yellow;width: 50px; height: 50px;">&nbsp;</div>
		<div style="height: 2px;">&nbsp;</div>
		<div style="background: yellow;width: 50px; height: 50px;">&nbsp;</div>
		<div style="height: 2px;">&nbsp;</div>
		<div style="background: yellow;width: 50px; height: 50px;">&nbsp;</div>
		<div style="height: 2px;">&nbsp;</div>
		<div style="background: yellow;width: 50px; height: 50px;">&nbsp;</div>
		<div style="height: 5px;">&nbsp;</div>
		<div style="background: black;width: 50px; height: 50px;">&nbsp;</div>
	</div>
	<div class="span5">
		<div class="well">
			<div class="well-title">{{ $episode->series->name }} {{ $episode->game->name }} {{ $episode->seriesNumber }}: {{ $episode->title }} Winners</div>
			{{ Form::open() }}
				<table class="table table-condesnsed" id="winnerFields">
					<thead>
						<tr>
							<th>Player</th>
							<th>Team</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@if (count($episode->wins) > 0)
							@foreach ($episode->wins->winmorph as $winner)
								<?php
									if ($winner instanceof Member) {
										$memberId = $winner->id;
										$teamId   = null;
									} else {
										$memberId = null;
										$teamId   = $winner->id;
									}
								?>
								<tr id="template">
									<td>{{ Form::select('members[]', $members, $memberId) }}</td>
									<td>{{ Form::select('teams[] ', $teams, $teamId) }}</td>
									<td><a href="javascript: void(0);" onClick="removeRow(this);"><i class="icon-remove-sign"></i></a></td>
								</tr>
							@endforeach
						@else
							<tr id="template">
								<td>{{ Form::select('members[]', $members) }}</td>
								<td>{{ Form::select('teams[] ', $teams) }}</td>
								<td>&nbsp;</td>
							</tr>
						@endif
					</tbody>
				</table>
				{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary')) }}
				{{ Form::button('Add Row', array('class' => 'btn btn-small btn-primary', 'id' => 'addRow')) }}
				{{ Form::submit('Next Episode', array('class' => 'btn btn-small btn-primary', 'name' => 'nextEpisode')) }}
			{{ Form::close() }}
		</div>
	</div>
</div>
@section('js')
	<script>
		$('#addRow').click(function() {
			var tr = $('#template');
			var clone = tr.clone();

			clone.find('td:last').html('<a href="javascript: void(0);" onClick="removeRow(this);"><i class="icon-remove-sign"></i></a>');

			$('#winnerFields > tbody').append(clone);
		});

		function removeRow(object) {
			if ($('#winnerFields > tbody tr').length > 1) {
				$(object).parent().parent().remove();
			} else {
				$(object).parent().parent().find('select').val(0);
				$(object).remove()
			}
		}
	</script>
@stop