@if (!isset($newMessage))
	<div class="row-fluid">
		<div class="span12">
			<div class="btn-group">
				<a href="javascript:void(0);" class="btn btn-mini btn-primary">Reply</a>
				<a href="javascript:void(0);" onclick="markUnread('{{ $message->id }}', '{{ $message->folderId }}');" class="btn btn-mini btn-info">Mark as Unread</a>
				<a href="javascript:void(0);" onclick="delete('{{ $message->id }}');" class="btn btn-mini btn-danger">Delete</a>
			</div>
		</div>
	</div>
@endif
<div class="row-fluid">
	<div class="span12">
		<h4>{{ $message->title }}</h4>
		<small class="muted">
			From: {{ $message->sender->username }}<br />
			On: {{ $message->created_at }}
		</small>
		<br />
		<br />
		{{ BBCode::parse($message->content) }}
		<?php $newMessage = $message->child; ?>
		@if ($newMessage != null)
			<br />
			<br />
			<hr />
			<div class="folder">@include('message.getmessage', array('message' => $newMessage))</div>
		@endif
	</div>
</div>
@section('js')
	<script>
		var $tree = $('#inboundMessages');

		function markUnread(messageId, folderId) {
			$.post('messages/mark-read/0/'+ messageId);

			var node   = $tree.tree('getNodeById', messageId);

			var parent = node.parent;
			var count  = parseInt(parent.count);

			count = count + 1;

			$tree.tree(
				'updateNode',
				node,
				{
					label: '<i class="icon-circle-blank text-info"></i> '+ node.title
				}
			);
			changeNodeCount(node.parent, count);

			$('#message_icon_'+ messageId).children().toggleClass('icon-circle-blank').toggleClass('icon-circle');
		}
	</script>
@stop