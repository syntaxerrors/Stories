<div class="rowspan">
	<div class="span12">
		<div class="rowspan">
			<div class="span3">
				Total Posts:
			</div>
			<div class="span8">
				<div class="progress progress-striped">
					<div class="bar" style="width: 100%;">
						<strong><?=$user->postsCount?></strong>
					</div>
				</div>
			</div>
		</div>
		@foreach ($user->activeCharacters as $character)
			<?php
				$characterPostCount = $user->characterPostsCount($character->id);
				$percent            = percent($characterPostCount, $user->postsCount);

				if ($characterPostCount == 0 || $percent == 0) {
					continue;
				}
			?>
			<div class="rowspan">
				<div class="span3">
					Total Posts as <?=$character->name?>:
				</div>
				<div class="span8">
					<div class="progress progress-striped">
						<div class="bar" style="width: <?=percent($user->characterPostsCount($character->id), $user->postsCount)?>%;">
							<strong><?=$characterPostCount?></strong>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>