<div class="row-fluid">
	<div class="offset3 span6">
		<div class="well">
			<div class="well-title">{{ $user->username }}'s Profile</div>
			<div class="media">
				{{ HTML::image($user->gravitar, null, array('class'=> 'media-object pull-left', 'style' => 'width: 100px;')) }}
				<div class="media-body">
					<h4 class="media-heading">
						User Details
						@if ($user->id == $activeUser->id)
							<div class="pull-right">
								{{ HTML::link('user/account', 'Edit') }}
							</div>
						@endif
					</h4>
					<table class="table table-hover table-condensed">
						<tbody>
							<tr>
								<td style="width: 100px;"><strong>Username:</strong></td>
								<td>{{ $user->username }}</td>
							</tr>
							<tr>
								<td><strong>Full Name:</strong></td>
								<td>{{ $user->fullName }}</td>
							</tr>
							<tr>
								<td><strong>Email:</strong></td>
								<td>{{ HTML::mailto($user->email) }}</td>
							</tr>
							<tr>
								<td><strong>Join Date:</strong></td>
								<td>{{ date('F jS, Y \a\t h:ia', strtotime($user->created_at)) }}</td>
							</tr>
							<tr>
								<td><strong>Last Active:</strong></td>
								<td>{{ $user->lastActiveReadable }}</td>
							</tr>
							<tr>
								<td><strong>Status:</strong></td>
								<td>
									{{ ($user->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes'))
										? HTML::image('img/icons/online.png', 'Online', array('title' => 'Online')) .' Online'
										: HTML::image('img/icons/offline.png', 'Offline', array('title' => 'Offline')) .' Offline'
									) }}
								</td>
							</tr>
							@if ($user->id != $activeUser->id)
								<tr>
									<td><strong>Send PM:</strong></td>
									<td><a href="#composeMessageModal" data-remote="/messages/compose/0/null/{{ $user->id }}" role="button" data-toggle="modal">Compose</a></td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

{{ Form::open(array('id' => 'composeMessage')) }}
	@include('helpers.modalHeader', array('modalId' => 'composeMessageModal', 'modalHeader' => 'Compose'))
	@section('modalFooter')
		@parent
		<button class="btn btn-primary" id="composeSubmit" aria-hidden="true">Submit</button>
		<div id="composeStatusMessage"></div>
	@stop
	@include('helpers.modalFooter')
{{ Form::close() }}

<script>
	$('#composeSubmit').on('click', function(event) {
		event.preventDefault();
		$('#composeSubmit').attr('disabled', 'disabled');

		$('.error').removeClass('error');
		$('#composeStatusMessage').empty().append('<i class="icon-spinner icon-spin"></i>');

		var data = $('#composeMessage').serialize();

		$.post('/messages/compose', data, function(response) {

			if (response.status == 'success') {
				$('#composeStatusMessage').empty().append('Message sent.');

				// Make the modal go away
				window.setTimeout(function () {
					$('#composeMessageModal').modal('hide');
					$('#composeMessageModal').removeData('modal');
					$('#composeSubmit').removeAttr('disabled');
					$('#composeStatusMessage').empty();
				}, 2000);
			}
			if (response.status == 'error') {
				$('#composeStatusMessage').empty();
				$.each(response.errors, function (key, value) {
					$('#' + key).addClass('error');
					$('#composeStatusMessage').append('<span class="text-error">'+ value +'</span><br />');
				});
			}
		});
	});
</script>