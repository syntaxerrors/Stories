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
			<div class="btn-group">
				<a href="#composeMessageModal" data-remote="/messages/compose" role="button" data-toggle="modal" class="btn btn-mini btn-primary">Compose</a>
				<a href="#addFolderModal" data-remote="/messages/add-folder" role="button" data-toggle="modal" class="btn btn-mini btn-primary">Add Folder</a>
			</div>
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

<div class="dropdown">
	<ul id="myMenu" class="dropdown-menu" role="menu" aria-labelledby="dLabel">
		<li class="edit"><a href="#edit"><i class="icon-edit"></i> Edit</a></li>
		<li class="delete"><a href="#delete"><i class="icon-remove"></i> Delete</a></li>
	</ul>
</div>


<?php $replyFlag = 0; ?>
{{ Form::open(array('id' => 'composeMessage')) }}
	@include('helpers.modalHeader', array('modalId' => 'composeMessageModal', 'modalHeader' => 'Compose'))
	@section('modalFooter')
		@parent
		<button class="btn btn-primary" id="composeSubmit" aria-hidden="true">Submit</button>
		<div id="composeStatusMessage"></div>
	@stop
	@include('helpers.modalFooter')
{{ Form::close() }}

{{ Form::open(array('id' => 'addFolder')) }}
	@include('helpers.modalHeader', array('modalId' => 'addFolderModal', 'modalHeader' => 'Add Folder'))
	@section('modalFooter')
		@parent
		<button class="btn btn-primary" id="folderSubmit" aria-hidden="true">Submit</button>
		<div id="folderStatusMessage"></div>
	@overwrite
	@include('helpers.modalFooter')
{{ Form::close() }}

@section('jsInclude')
	{{ HTML::script('/vendors/jqTree/tree.jquery.js') }}
	{{ HTML::script('/vendors/jqTreeContextMenu/jqTreeContextMenu.js') }}
@stop
@section('js')
	<script>
		var treeObject = [{{ json_encode($rootNode) }}];
		var $tree = $('#inboundMessages');

		var jqMenu = $tree.jqTreeContextMenu($('#myMenu'), {
			"edit": function (node) {
				$tree.tree('selectNode', null);
				alert('Edit '+ node.type +': ' + node.id);
			},
			"delete": function (node) {
				$tree.tree('selectNode', null);
				alert('Delete '+ node.type +': ' + node.id);
			}
		});

		$(function () {
			// Set up the tree
			$tree.tree({
				data: treeObject,
				dragAndDrop: true,
				autoEscape: false,
				selectable: false,
				autoOpen: 1,
				usecontextmenu: true,
				openedIcon: '<i class="icon-folder-open"></i>',
				closedIcon: '<i class="icon-folder-close"></i>',
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

			// $tree.bind(
			// 	'tree.load_data',
			// 	function(e) {
			// 		console.log(e.tree_data);
			// 		var node = $tree.tree('getNodeById', e.tree_data[0].id);
			// 		$tree.tree('openNode', node);
			// 	}
			// );

			$tree.bind(
				'tree.contextmenu',
				function(e) {
					console.log(e.node.type);
					if (e.node.type == 'message') {
						jqMenu.disable(e.node.name, ['edit']);
					}else if (e.node.type == 'placeholder') {
						jqMenu.disable(e.node.name, ['edit', 'delete']);
					}
				}
			);

			// Change what happens when an element is clicked.
			$tree.bind(
				'tree.click',
				function(e) {
					e.preventDefault();
					var node     = e.node;
					var nodeType = node.type;

					// For messages, show the contents of the message
					if (nodeType == 'message') {
						showMessage(node.id, node.parent.id);

						if (node.readFlag != 1) {
							var parent = node.parent;
							var count  = parseInt(parent.count);

							if (count > 0) {
								count = count - 1;
							}

							// Update the message to use the read icon
							$tree.tree(
								'updateNode',
								node,
								{
									label: '<i class="icon-circle text-info"></i> '+ node.title,
									readFlag: 1
								}
							);

							// Update the unread count on the folder
							changeNodeCount(node.parent, count);
						}
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

		$('#composeSubmit').on('click', function(event) {
			event.preventDefault();

			$('.error').removeClass('error');
			$('#composeStatusMessage').empty().append('<i class="icon-spinner icon-spin"></i>');

			var data = $('#composeMessage').serialize();

			$.post('/messages/compose', data, function(response) {

				if (response.status == 'success') {
					$('#composeStatusMessage').empty().append('Message sent.');

					// Make the modal go away
					window.setTimeout(function () {$('#composeMessageModal').modal('hide');}, 2000);
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

		$('#folderSubmit').on('click', function(event) {
			event.preventDefault();

			$('.error').removeClass('error');
			$('#folderStatusMessage').empty().append('<i class="icon-spinner icon-spin"></i>');

			var data = $('#addFolder').serialize();

			$.post('/messages/add-folder', data, function(response) {

				if (response.status == 'success') {
					$('#folderStatusMessage').empty().append('Folder created.');

					// Add the new node
					var newFolderParentNode = $tree.tree('getNodeById', '{{ $inbox }}');

					$tree.tree(
						'appendNode',
						response.data.folder,
						newFolderParentNode
					);

					// Make the modal go away
					window.setTimeout(function () {$('#addFolderModal').modal('hide');}, 2000);
				}
				if (response.status == 'error') {
					$('#folderStatusMessage').empty();
					$.each(response.errors, function (key, value) {
						$('#' + key).addClass('error');
						$('#folderStatusMessage').append('<span class="text-error">'+ value +'</span><br />');
					});
				}
			});
		});
	</script>
@stop