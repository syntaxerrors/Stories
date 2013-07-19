<?php $create = false; ?>
@if ($activeUser->checkPermission('CHAT_CREATE'))
	<?php $create = true; ?>
@endif
<div class="row-fluid">
	<div class="offset2 span8">
		<div class="well">
			<div class="well-title">Chat Rooms</div>
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th class="text-left">Room</th>
						<th class="text-left">User Online</th>
						<th class="text-left">Game</th>
						<th class="text-left">Creator</th>
						@if ($create)
							<th class="text-right">Actions</th>
						@endif
					</tr>
				</thead>
				<tbody>
					@if (count($chatRooms) > 0)
						@foreach ($chatRooms as $chatRoom)
							<tr>
								<td><?=HTML::link('chat/room/'. $chatRoom->uniqueId, $chatRoom->name)?></td>
								<td><?=count($chatRoom->usersOnline)?></td>
								<td>
								</td>
								<td><?=HTML::link('profile/user/'. $chatRoom->user_id, $chatRoom->user->username)?></td>
								@if ($create)
									<td class="text-right">
										<div class="btn-group">
											<?=HTML::link('chat/clear/'. $chatRoom->uniqueId, 'Clear Chats', array('class' => 'btn btn-mini btn-primary'))?>
											<?=HTML::link('chat/update/'. $chatRoom->uniqueId .'/activeFlag/0', 'Make Inactive', array('class' => 'btn btn-mini btn-primary'))?>
											<?=HTML::link('chat/delete/'. $chatRoom->uniqueId, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger'))?>
										</div>
									</td>
								@endif
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
		@if ($create && count($inactiveChatRooms) > 0)
			<div class="well">
				<div class="well-title">Inactive Chat Rooms</div>
				<table class="table table-condensed table-hover table-striped">
					<thead>
						<tr>
							<th class="text-left">Room</th>
							<th class="text-left">User Online</th>
							<th class="text-left">Game</th>
							<th class="text-left">Creator</th>
							@if ($create)
								<th class="text-right">Actions</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@if (count($inactiveChatRooms) > 0)
							@foreach ($inactiveChatRooms as $chatRoom)
								<tr>
									<td><?=HTML::link('chat/room/'. $chatRoom->uniqueId, $chatRoom->name)?></td>
									<td><?=count($chatRoom->usersOnline)?></td>
									<td>
									</td>
									<td><?=HTML::link('profile/user/'. $chatRoom->user_id, $chatRoom->user->username)?></td>
									@if ($create)
										<td class="text-right">
											<div class="btn-group">
												<?=HTML::link('chat/clear/'. $chatRoom->uniqueId, 'Clear Chats', array('class' => 'btn btn-mini btn-primary'))?>
												<?=HTML::link('chat/update/'. $chatRoom->uniqueId .'/activeFlag/1', 'Make Active', array('class' => 'btn btn-mini btn-primary'))?>
												<?=HTML::link('chat/delete/'. $chatRoom->uniqueId, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger'))?>
											</div>
										</td>
									@endif
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
		@endif
	</div>
</div>