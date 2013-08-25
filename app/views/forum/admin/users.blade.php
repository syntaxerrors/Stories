<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Forum Users</div>
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>Username</th>
						<th>Existing Role</th>
						<th>New Role</th>
						<th>Actions</th>
						<th style="width: 200px;">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($users as $user)
						<tr>
							<td>{{ $user->username }}</td>
							<td id="role{{ $user->id }}">{{ $user->highestRole->name }}</td>
							@if (!$user->roles->contains(User_Permission_Role::DEVELOPER) && !$user->roles->contains(User_Permission_Role::FORUM_ADMIN))
								<td>{{ Form::select('nwRoleId', $user->higherRoles, null, array('id' => 'user'. $user->id)) }}</td>
								<td><a href="javascript: void(0);" class="confirm-continue btn btn-mini btn-primary" onclick="updateRole('{{ $user->id }}');">Update Role</a></td>
							@else
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							@endif
							<td>
								<div id="message{{ $user->id }}"></div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@section('js')
	<script>
		function updateRole(userId) {
			var newRoleId = $('#user'+ userId).val();

			$('#message'+ userId).empty().append('<i class="icon-spinner icon-spin"></i>');

			$.post('/forum/admin/update-role/' + userId +'/'+ newRoleId, function(response) {

				if (response.status == 'success') {
					$('#role'+ userId).empty().append(response.data.role);
					$('#message'+ userId).empty().append('<span>Forum role updated.</span>').children().fadeOut(5000);
				}
				if (response.status == 'error') {
					$('#message'+ userId).empty();
					$.each(response.errors, function (key, value) {
						$('#' + key).addClass('error');
						$('#message'+ userId).append('<span class="text-error">'+ value +'</span><br />');
					});
				}
			});
		}
	</script>
@stop