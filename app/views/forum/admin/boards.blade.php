<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Forum Boards</div>
			@foreach ($categories as $category)
				<h5>{{ $category->name }}</h5>
				<table class="table table-condensed table-striped table-hover" id="boards_{{ $category->id }}">
					<thead>
						<tr>
							<th style="width: 2%;">&nbsp;</th>
							<th style="width: 49%;">Name</th>
							<th style="width: 49%;">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($category->boards as $board)
							<tr id="board_{{ $board->id }}">
								<td><i class="icon-resize-vertical" title="Change order"></i></td>
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
						@endforeach
					</tbody>
				</table>
				<div>
					<a href="javascript: void(0);" class="btn btn-mini btn-primary" onclick="changeOrder('{{ $category->id }}');">Change the board orders</a>
				</div>
				<hr />
			@endforeach
		</div>
	</div>
</div>
@section('css')
	{{ HTML::style('/vendors/xEditable/bootstrap-editable/css/bootstrap-editable.css') }}
@stop
@section('jsInclude')
	{{ HTML::script('/vendors/jQuery/ui/js/jquery-ui-1.10.2.custom.min.js') }}
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

		// Return a helper with preserved width of cells
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		}

		var categoryIds = {{ $categories->id->toJson() }};

		$.each(categoryIds, function(categoryId) {
			$(function() {
				$('#boards_'+ categoryId +' tbody').sortable({ helper: fixHelper, opacity: 0.6, cursor: 'move'}).disableSelection();
			});
		});
		@parent
	@stop
</script>
@section('js')
	<script>
		function changeOrder(categoryId) {
			bootbox.confirm("Are you sure you want to continue?", "No", "Yes", function(confirmed) {
				if(confirmed) {
					// Submit the new order to the database
					var order = $('#boards_'+ categoryId +' tbody').sortable('serialize');
					$.post('/forum/admin/set-board-order', order, function(theResponse){
						Messenger().post({message: theResponse});
					});
				}
			});
		}
	</script>
@stop