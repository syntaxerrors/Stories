<div class="row-fluid">
	<div class="span5">
		<div class="well">
			<div class="well-title">
				<div class="well-btn well-btn-left">
					<a href="javascript: void(0);" class="options"><i class="icon-cog"></i>&nbsp;Options</a>
				</div>
				Win Count
			</div>
			@if (count($winDetails) > 0)
				@foreach ($winDetails as $winner => $details)
					<div class="collapse-group">
						<div class="row-fluid active">
							<div class="span1 text-primary" style="width: 12px;">
								<i class="icon-large icon-expand" id="winDetails"></i>
							</div>
							<div class="span10">{{ $winner }}</div>
							<div class="span1">{{ $details->count }}</div>
						</div>
						<div class="row-fluid collapse">
							<div class="span12">
								<table class="table table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th>Series</th>
											<th>Game</th>
											<th>Title</th>
											<th>Date</th>
											<th class="text-center">YouTube</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($details->object as $win)
											<tr>
												<td>{{ $win->episode->series->name }}</td>
												<td>{{ $win->episode->game->name }} Ep. {{ $win->episode->seriesNumber }}</td>
												<td>{{ $win->episode->title }}</td>
												<td>{{ date('M jS, Y', strtotime($win->episode->date)) }}</td>
												<td class="text-center">
													<a href="http://www.youtube.com/watch?v={{ $win->episode->link }}" target="_blank"><i class="icon-2x icon-youtube"></i></a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
	<div class="span3">
		<a href="{{ $playlist }}" target="_blank"><i class="icon-youtube"></i>&nbsp; Watch as playlist</a>
	</div>
</div>
<div class="well">
	<div class="well-title">
		Scoreboard
	</div>
	<table class="table table-hover table-condensed table-striped">
		<thead>
			<tr>
				<th>Series</th>
				<th>Game</th>
				<th>Episode</th>
				<th>Title</th>
				<th>Winners</th>
				<th class="text-center">YouTube</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($episodes as $episode)
				<tr>
					<td>{{ $episode->series->name }}</td>
					<td>{{ $episode->game->name }}</td>
					<td>{{ $episode->seriesNumber }}</td>
					<td>{{ $episode->title }}</td>
					<td>{{ implode(', ', $episode->wins->winmorph->name->toArray()) }}</td>
					<td class="text-center">
						<a href="http://www.youtube.com/watch?v={{ $episode->link }}" target="_blank"><i class="icon-2x icon-youtube"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@section('js')
	<script>
		$('.options').click(function() {
			$('#config').toggle('slow');
			$('#scores').toggleClass('span12 span8');
			$('#scores').toggleClass('no-sidebar');
		});

		$('.row-fluid #winDetails').on('click', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $collapse = $this.closest('.collapse-group').find('.collapse');

			$this.toggleClass('icon-expand icon-collapse')
			$collapse.collapse('toggle');
		});
	</script>
@stop