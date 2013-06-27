<div class="row-fluid">
	<div class="offset2 span8">
		<div class="well">
			<div class="well-title">
				<div class="well-btn well-btn-right">
					{{ HTML::linkIcon('/manage/add', 'icon-plus-sign') }}
				</div>
				AH Episodes
			</div>
			<table class="table table-hover table-condensed table-striped">
				<thead>
					<tr>
						<th>Series</th>
						<th>Game</th>
						<th>Episode</th>
						<th>Title</th>
						<th class="text-center">YouTube</th>
						<th>Date</th>
						<th class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($episodes as $episode)
						{{ pp($episode->wins) }}
						<tr>
							<td>{{ $episode->series->name }}</td>
							<td>{{ $episode->game->name }}</td>
							<td>{{ $episode->seriesNumber }}</td>
							<td>{{ stripslashes($episode->title) }}</td>
							<td class="text-center"><a href="http://www.youtube.com/watch?v={{ $episode->link }}" target="_blank"><i class="icon-2x icon-youtube"></i></td>
							<td>{{ date('F jS, Y', strtotime($episode->date)) }}</td>
							<td class="text-center">
								<div class="btn-group">
									{{ HTML::link('/manage/winners/'. $episode->id, 'Winners', array('class' => 'btn btn-mini btn-primary')) }}
									{{ HTML::link('/manage/edit/'. $episode->id, 'Edit', array('class' => 'btn btn-mini btn-primary')) }}
									{{ HTML::link('/manage/delete/'. $episode->id, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>