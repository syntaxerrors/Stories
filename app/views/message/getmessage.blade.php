@if (!isset($newMessage))
	<div class="row-fluid">
		<div class="span12">
			<div class="btn-group">
				<a href="#replyMessageModal" data-remote="/messages/compose/1/{{ $message->id }}" role="button" data-toggle="modal" class="btn btn-mini btn-primary">Reply</a>
				<a href="javascript:void(0);" onclick="markUnread('{{ $message->id }}', '{{ $message->folderId }}');" class="btn btn-mini btn-info">Mark as Unread</a>
				<a href="javascript:void(0);" onclick="deleteMessage('{{ $message->id }}');" class="btn btn-mini btn-danger">Delete</a>
			</div>
		</div>
	</div>
@endif
<div class="row-fluid">
	<div class="span12">
		<h4>{{ $message->title }}</h4>
		<small class="muted">
			From: {{ HTML::link('/user/view/'. $message->sender_id, $message->sender->username, array('target' => '_blank')) }}<br />
			To: {{ HTML::link('/user/view/'. $message->receiver_id, $message->receiver->username, array('target' => '_blank')) }}<br />
			On: {{ $message->created_at }}
		</small>
		<br />
		<br />
		{{ Utility_Response_BBCode::parse($message->content) }}
		<?php $newMessage = $message->child; ?>
		@if ($newMessage != null)
			<br />
			<br />
			<hr />
			<div class="folder">@include('message.getmessage', array('message' => $newMessage))</div>
		@endif
	</div>
</div>

@if ($message->parent_id == null)
	{{ Form::open(array('id' => 'replyMessage')) }}
		@include('helpers.modalHeader', array('modalId' => 'replyMessageModal', 'modalHeader' => 'Reply', ))
		@section('modalFooter')
			@parent
			<button class="btn btn-primary" id="replySubmit" aria-hidden="true">Submit</button>
			<div id="replyStatusMessage"></div>
		@stop
		@include('helpers.modalFooter')
	{{ Form::close() }}

	<script>
		$('#replySubmit').on('click', function(event) {
			event.preventDefault();
			$('#replySubmit').attr('disabled', 'disabled');

			$('.error').removeClass('error');
			$('#replyStatusMessage').empty().append('<i class="icon-spinner icon-spin"></i>');

			var data = $('#replyMessage').serialize();

			$.post('/messages/compose', data, function(response) {

				if (response.status == 'success') {
					$('#replyStatusMessage').empty().append('Reply sent.');

					// Make the modal go away
					window.setTimeout(function () {
						$('#replyMessageModal').modal('hide');
						$('#replyMessageModal').removeData('modal');
						$('#replySubmit').removeAttr('disabled');
						$('#replyStatusMessage').empty();
					}, 2000);
				}
				if (response.status == 'error') {
					$('#replyStatusMessage').empty();
					$.each(response.errors, function (key, value) {
						$('#' + key).addClass('error');
						$('#replyStatusMessage').append('<span class="text-error">'+ value +'</span><br />');
					});
				}
			});
		});

		function deleteMessage(messageId) {
			bootbox.confirm("Are you sure you want to remove this item?", "No", "Yes", function(confirmed) {
				if(confirmed) {
					var $tree = $('#inboundMessages');
					var node = $tree.tree('getNodeById', messageId);

					$.post('/messages/delete-message/'+ messageId);

					$tree.tree(
						'removeNode',
						node
					);

					Messenger().post({message: 'Message deleted'});

					$('#messageContents').empty();
				}
			});
		}
	</script>
@endif

@section('js')
	<script>
		var $tree = $('#inboundMessages');

		function markUnread(messageId, folderId) {
			var node   = $tree.tree('getNodeById', messageId);

			if (node.readFlag != 0) {
				$.post('messages/mark-read/0/'+ messageId);

				var parent = node.parent;
				var count  = parseInt(parent.count);

				count = count + 1;

				$tree.tree(
					'updateNode',
					node,
					{
						label: '<i class="icon-circle-blank text-info"></i> '+ node.title,
						readFlag: 0
					}
				);
				changeNodeCount(node.parent, count);

				$('#message_icon_'+ messageId).children().toggleClass('icon-circle-blank').toggleClass('icon-circle');

			}
		}
	</script>
@stop