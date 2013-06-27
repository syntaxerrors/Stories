<div class="row-fluid">
	<div class="offset3 span1" style="height: 260px;">
		<div style="background: yellow;width: 50px; height: 50px; border-bottom: 2px;">&nbsp;</div>
		<div style="background: yellow;width: 50px; height: 50px;">&nbsp;</div>
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
						<tr id="template">
							<td>{{ Form::select('members[]', $members) }}</td>
							<td>{{ Form::select('teams[] ', $teams) }}</td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
				{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary')) }}
				{{ Form::button('Add Row', array('class' => 'btn btn-small btn-primary', 'id' => 'addRow')) }}
			{{ Form::close() }}
		</div>
	</div>
</div>
@section('js')
	<script>
		$('#addRow').click(function() {
			var tr = $('#template');
			var clone = tr.clone();

			clone.find('td:last').html('<a href="javascript: void(0);" onClick="$(this).parent().parent().remove();"><i class="icon-remove-sign"></i></a>');

			$('#winnerFields > tbody').append(clone);
		});
	</script>
@stop