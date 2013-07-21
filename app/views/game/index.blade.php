<div class="row-fluid">
	<div class="offset1 span10">
		<div class="well">
			<div class="well-title">Games</div>
			<table class="table table-hover table-striped text-center">
				<thead>
					<tr>
						<th>Name</th>
						<th class="text-center">Story-Tellers</th>
						<th class="text-center">Characters</th>
						<th class="text-center">Forum</th>
						<th class="text-center">Active</th>
						<th class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($games as $game)
						<tr>
							<td class="text-left"><?=$game->name?></td>
							<td class="text-center">
								<a href="#modal_<?=$game->id?>_st" role="button" class="btn btn-mini btn-primary" data-toggle="modal"><?=count($game->storytellers)?></a>
								<div id="modal_<?=$game->id?>_st" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h3 id="myModalLabel">Story-Tellers for <?=$game->name?></h3>
									</div>
									<div class="modal-body">
										@if (count($game->storytellers) > 0)
											@foreach ($game->storytellers as $storyteller)
												<div class="well well-small">
													<?=HTML::link('profile/user/'. $storyteller->user->id, $storyteller->user->username)?>
												</div>
											@endforeach
										@else
											<p>No characters.</p>
										@endif
									</div>
									<div class="modal-footer">
										<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
									</div>
								</div>
							</td>
							<td class="text-center">
							</td>
							<td class="text-center">
								@if ($game->forum != null)
									<?=HTML::link('forum/category/view/'. $game->forum->id, 'Forum Page', array('class' => 'btn btn-mini btn-primary'))?>
								@else
									<?=HTML::link('forum/category/add/'. $game->id, 'Create Forum', array('class' => 'btn btn-mini btn-primary'))?>
								@endif
							</td>
							<td class="text-center">
								@if ($game->isStoryteller($activeUser->id))
									<?=HTML::linkIcon('game/index/'. $game->id .'/activeFlag/'. ($game->activeFlag == 1 ? 0 : 1), ($game->activeFlag == 1 ? 'icon-ok icon-large' : 'icon-remove icon-large'), null, array('class' => 'btn btn-mini btn-primary'))?></td>
								@endif
							<td class="text-right">
								@if ($game->isStoryteller($activeUser->id))
								<div class="btn-group">
									<?=HTML::link('game/edit/'. $game->id, 'Edit', array('class' => 'btn btn-mini btn-primary'))?>
									<?=HTML::link(strtolower($game->type->keyName) .'/'. $game->id, 'Manage', array('class' => 'btn btn-mini btn-primary'))?>
									<?=HTML::link('game/delete/'. $game->id, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger'))?>
								</div>
								@endif
							</td>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>