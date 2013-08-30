@section('css')
	{{ HTML::style('/vendors/jqTree/jqtree.css') }}
@stop
<style type="text/css">
	.folder {
		padding-left: 20px;
	}
</style>
<div class="row-fluid">
	<div class="span3">
		<div class="well">
			<a href="javascript: void(0);" class="btn btn-mini btn-primary">Compose</a>
			<br />
			<br />
			<div id="inboundMessages"></div>
		</div>
	</div>
	<div class="span9">
		<div class="well">
			<div id="messageContents"></div>
		</div>
	</div>
</div>

@section('jsInclude')
	{{ HTML::script('/vendors/jQuery/ui/js/jquery-ui-1.10.2.custom.min.js') }}
	{{ HTML::script('/vendors/jqTree/tree.jquery.js') }}
	{{ HTML::script('/vendors/jqTree/extra/js/jquery.cookie.js') }}
@stop
@section('js')
	<script>
		var treeObject = [];
		var $tree = $('#inboundMessages');

		$(function () {
			// Set up the tree
			$tree.tree({
				dragAndDrop: true,
				saveState: true,
				autoEscape: false,
				dataUrl: 'messages/get-messages-for-folder/{{ $activeUser->id }}',
				onCanMove: function(node) {
					if (node.type == 'folder' || node.type == 'placeholder') {
						return false;
					} else {
						return true;
					}
				},
				onCanMoveTo: function(moved_node, target_node, position) {
					if (target_node.type == 'folder' && position == 'inside') {
						if (target_node.id == moved_node.parent.id) {
							return false;
						}
						return true;
					} else {
						return false;
					}
				}
			});

			// Change what happens when an element is clicked.
			$tree.bind(
				'tree.click',
				function(e) {
					e.preventDefault();
					var node     = e.node;
					var nodeType = node.type;

					// For messages, show the contents of the message
					if (nodeType == 'message') {
						var parent = node.parent;
						var count  = parseInt(parent.count);

						if (count > 0) {
							count = count - 1;
						}

						showMessage(node.id, node.parent.id);

						// Update the message to use the read icon
						$tree.tree(
							'updateNode',
							node,
							{
								label: '<i class="icon-circle text-info"></i> '+ node.title
							}
						);

						// Update the unread count on the folder
						changeNodeCount(node.parent, count);
					}
				}
			);

			// Handle node movement
			$tree.bind(
				'tree.move',
				function(e) {
					// Set the variables
					var previousParent = e.move_info.previous_parent;
					var newParent      = e.move_info.target_node;

					var messageId      = e.move_info.moved_node.id;
					var parentFolderId = previousParent.id;
					var newFolderId    = newParent.id;

					// Update the database with the move
					$.post('messages/move-message/'+ messageId +'/'+ parentFolderId +'/'+ newFolderId);

					// Handle removing and creating placeholders
					// Remove any existing placeholder in the target node
					$.each(newParent.children, function() {
						if (this.type == 'placeholder') {
							$tree.tree('removeNode', this);
						}
					});

					var validChildren = 0;

					// See if the previous node has any messages left
					if (previousParent.children.length > 0) {
						$.each(previousParent.children, function(child) {
							if (child.type == 'message') {
								validChildren = validChildren + 1;
							}
						});
					}

					// If the previous node has no messages, add a placeholder
					if (validChildren == 0) {
						var date = new Date();
						$tree.tree(
							'appendNode',
							{
								label: 'No messages to display',
								id: 0 + date.toISOString(),
								selectable: false,
								type: 'placeholder'
							},
							previousParent
						);
					}

					// Force the node to the end of the parent node
					// Duplicate the moved node
					var movedNode = e.move_info.moved_node;

					// Remove the original moved node
					$tree.tree('removeNode', e.move_info.moved_node);

					// Append the clone to the end of the target node
					$tree.tree(
						'appendNode',
						movedNode,
						newParent
					);

					// Update the count on the two folders
					var previousParentCount = parseInt(previousParent.count) - 1;
					var newParentCount      = parseInt(newParent.count) + 1;

					changeNodeCount(previousParent, previousParentCount);
					changeNodeCount(newParent, newParentCount);

					// Prevent tree.js from continuing
					e.preventDefault();
				}
			);
		});

		function showMessage(messageId, folderId) {
			// Update the DB
			$.post('messages/mark-read/1/'+ messageId);

			// Get the contents of the message
			$.ajax({
				url: '/messages/get-message/'+ messageId,
				type: "GET",
				success: function (data, textStatus, jqxhr) {
					$('#messageContents').empty().html(data);
				}
			});
		}

		function changeNodeCount(node, count) {
			// Update the unread count on the folder
			$tree.tree(
				'updateNode',
				node,
				{
					label: node.title +' ('+ count +')',
					count: count
				}
			);
		}
	</script>
@stop