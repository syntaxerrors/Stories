<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Forum Boards</div>
			@foreach ($categories as $category)
				<?php $boardsWithChildren = array(); ?>
				<h5>{{ $category->name }}</h5>
				<table class="table table-condensed table-striped table-hover" id="category_{{ $category->id }}">
					<thead>
						<tr>
							<th style="width: 2%;">&nbsp;</th>
							<th style="width: 49%;">Name</th>
							<th style="width: 49%;">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($category->boards as $board)
							@if ($board->parent_id != null)
								@continue
							@endif
							<tr id="{{ $board->id }}">
								<td style="cursor: move;"><i class="icon-resize-vertical" title="Change order"></i></td>
								<td>
									<a href="javascript: void(0);" class="editable" id="name" data-type="text" data-pk="{{ $board->id }}">
										{{ $board->name }}
									</a>
								</td>
								<td>
									<div class="btn-group">
										{{ HTML::link('forum/admin/delete-board/'. $board->id, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
									</div>
								</td>
							</tr>
							@if ($board->children->count() > 0)
								<?php $boardsWithChildren[] = $board; ?>
							@endif
						@endforeach
					</tbody>
				</table>
				@if (count($boardsWithChildren) > 0)
					<div style="margin-left: 20px;">
						<h5 class="text-info">Child Boards</h5>
						@foreach ($boardsWithChildren as $board)
							<h6>{{ $board->name }}</h6>
							<table class="table table-inner table-condensed table-striped table-hover" id="board_{{ $board->id }}">
								<thead>
									<tr>
										<th style="width: 2%;">&nbsp;</th>
										<th style="width: 49%;">Name</th>
										<th style="width: 49%;">Actions</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($board->children as $child)
										<tr id="{{ $child->id }}">
											<td style="cursor: move;"><i class="icon-resize-vertical" title="Change order"></i></td>
											<td>
												<a href="javascript: void(0);" class="editable" id="name" data-type="text" data-pk="{{ $child->id }}">
													{{ $child->name }}
												</a>
											</td>
											<td>
												<div class="btn-group">
													{{ HTML::link('forum/admin/delete-board/'. $child->id, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@endforeach
					</div>
				@endif
				<hr />
			@endforeach
		</div>
	</div>
</div>
@section('css')
	{{ HTML::style('/vendors/xEditable/bootstrap-editable/css/bootstrap-editable.css') }}
@stop
@section('jsInclude')
	{{ HTML::script('/vendors/tableDnD/jquery.tablednd.js') }}
	{{ HTML::script('/vendors/xEditable/bootstrap-editable/js/bootstrap-editable.js') }}
@stop
<script>
	@section('onReadyJs')
		// X-Editable details
		$.fn.editable.defaults.mode        = 'inline';
		$.fn.editable.defaults.url         = '/forum/admin/board-edit';
		$.fn.editable.defaults.showbuttons = false;
		$.fn.editable.defaults.class       = false;
		$('.editable').editable();

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		}

		var categoryIds = {{ $categories->id->toJson() }};
		var boardIds    = {{ $boards->id->toJson() }};

		$.each(categoryIds, function(key, categoryId) {
			$(function() {
				$('#category_'+ categoryId).tableDnD({
					onDragClass: 'primary',
					dragHandle: '.icon-resize-vertical',
					onDrop: function(table, row) {
						$.post('/forum/admin/move-boards', $.tableDnD.serialize());
					}
				});
			});
		});

		$.each(boardIds, function(key, boardId) {
			$(function() {
				$('#board_'+ boardId).tableDnD({
					onDragClass: 'primary',
					dragHandle: '.icon-resize-vertical',
					onDrop: function(table, row) {
						$.post('/forum/admin/move-boards', $.tableDnD.serialize());
					}
				});
			});
		});
	@stop
</script>