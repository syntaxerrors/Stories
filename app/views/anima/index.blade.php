<div class="row-fluid">
	<div class="span12">
		<small>
			<ul class="breadcrumb">
				<li class="active">Manage Game <span class="divider">/</span></li>
				<li><a href="javascript: void(0);">Players</a> <span class="divider">/</span></li>
				<li><a href="javascript: void(0);">Story-Tellers</a> <span class="divider">/</span></li>
				<li><a href="javascript: void(0);">Horde Builder</a></li>
			</ul>
		</small>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<div class="well">
			<div class="well-title">Awaiting Story-Teller Attention</div>
			@if (count($game->unApprovedTrees) > 0)

				@include('game.components.approval.magictrees')

			@endif
			@if (count($game->unApprovedSpells) > 0)

				@include('game.components.approval.newspells')

			@endif
			@if (count($game->unApprovedCharacterSpells) > 0)

				@include('game.components.approval.spellaccess')

			@endif
			@if (count($game->charactersAwaitingApproval) > 0)

				@include('game.components.approval.applications')

			@endif
			@if (count($game->actionsAwaitingApproval) > 0)

				@include('game.components.approval.actionposts')

			@endif
		</div>
		<div class="well">
			<div class="well-title">
				<a class="accordion-toggle" data-toggle="collapse" href="#collapseCharacters" style="color: #000;" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
					Characters <i class="icon-chevron-down"></i>
				</a>
				<div class="well-btn well-btn-right">
					<?=HTML::linkIcon('game/character/add/'. $game->slug, 'icon-plus')?>
				</div>
			</div>
			<div id="collapseCharacters" class="accordion-body collapse">

				@include('game.components.characters')

			</div>
		</div>
		<div class="well">
			<div class="well-title">
				<a class="accordion-toggle" data-toggle="collapse" href="#collapseEnemies" style="color: #000;" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
					Enemies <i class="icon-chevron-down"></i>
				</a>
				<div class="well-btn well-btn-right">
					<?=HTML::linkIcon('game/enem/add/'. $game->slug .'/1', 'icon-plus')?>
				</div>
			</div>
			<div id="collapseEnemies" class="accordion-body collapse">
				ENEMIES PLACEHOLDER
			</div>
		</div>
		<div class="well">
			<div class="well-title">
				<a class="accordion-toggle" data-toggle="collapse" href="#collapseEntities" style="color: #000;" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
					Entities <i class="icon-chevron-down"></i>
				</a>
				<div class="well-btn well-btn-right">
					<?=HTML::linkIcon('game/character/add/'. $game->slug, 'icon-plus')?>
				</div>
			</div>
			<div id="collapseEntities" class="accordion-body collapse">
				ENTITIES PLACEHOLDER
			</div>
		</div>
		<div class="well">
			<div class="well-title">
				<a class="accordion-toggle" data-toggle="collapse" href="#collapsedead" style="color: #000;" onClick="$(this).children().toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');">
					Dead/Inactive Characters <i class="icon-chevron-down"></i>
				</a>
			</div>
			<div id="collapsedead" class="accordion-body collapse">

				@include('game.components.characters')

			</div>
		</div>
	</div>
	<div class="span3">
		<div class="well">
			<div class="well-title">Recent Forum Activity</div>
			<table style="width: 100%;" class="table-hover">
				<tbody>
					@if (count($recentPosts) > 0)
						@foreach ($recentPosts as $post)
							<tr>
								<td class="text-center" style="width: 30px;">
									@if (isset($post->status->id))
										<?=$post->status->icon?>
									@else
										<?=$post->icon?>
									@endif
								</td>
								<td style="width: 100px;min-width: 100px;max-width: 100px;text-align: justify;text-overflow: ellipsis;word-wrap: break-word;white-space: nowrap;overflow: hidden;">
									<?=HTML::link('forum/post/view/'. $post->keyName, $post->name)?>
								</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<div class="well">
			<div class="well-title">Game Notes</div>
			<table style="width: 100%;" class="table-hover">
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?=Form::open()?>
	<?=Form::hidden('character_id', null, array('id' => 'exp_character_id'))?>
	<div id="grantExp" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Grant Experience to Player</h3>
		</div>
		<div class="modal-body text-center">
			<span id="exp_character_name"></span> currently has <span id="exp_character_exp"></span> experience
			<?=Form::text('exp', null, array('placeholder' => 'Experience Points', 'class' => 'span5', 'required' => 'required'))?>
			<?=Form::textarea('reason', null, array('placeholder' => 'Reason for Exp', 'class' => 'span5', 'required' => 'required'))?>
		</div>
		<div class="modal-footer">
			<?=Form::submit('Give Exp', array('class' => 'btn btn-mini btn-primary'))?>
			<button class="btn btn-mini btn-primary" data-dismiss="modal" aria-hidden="true" onClick="removeResources('exp')">Close</button>
		</div>
	</div>
<?=Form::close()?>
<script type="text/javascript">
	function removeResources(type) {
		$('#character_id').val('');
	}
</script>