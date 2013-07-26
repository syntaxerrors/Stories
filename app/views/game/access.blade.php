<div class="row-fluid">
	<div class="offset2 span8">
		<div class="accordion" id="accordion2">
			@foreach ($gameTypes as $gameType)
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#{{ $gameType->id }}">
							{{ $gameType->name }}
							<div class="pull-right">
								@if ($activeUser->checkPermission($gameType->keyName .'_ACCESS'))
									<span class="badge badge-success">Access Granted</span>
								@else
									<span class="badge badge-important">Request Access</span>
								@endif
							</div>
						</a>
					</div>
					<div id="{{ $gameType->id }}" class="accordion-body collapse clearfix">
						<div class="accordion-inner">
							<h4 class="text-primary">Games</h4>
							<table class="table table-condesnsed table-striped table-hover">
								<thead>
									<tr>
										<th>Name</th>
										<th>Forums</th>
										<th>Characters</th>
										<th>Story-Tellers</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($gameType->games as $game)
										<tr>
											<td>{{ $game->name }}</td>
											@if ($game->forum instanceof Forum_Category)
												<td>{{ $game->forum->name }}</td>
											@else
												<td>No Forum</td>
											@endif
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>