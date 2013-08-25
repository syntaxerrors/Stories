<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Forum Categories</div>
			<table class="table table-condensed table-striped table-hover" id="categories">
				<thead>
					<tr>
						<th style="width: 2%;">&nbsp;</th>
						<th style="width: 49%;">Name</th>
						<th style="width: 49%;">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
						<tr id="category_{{ $category->id }}">
							<td><i class="icon-resize-vertical" title="Change order"></i></td>
							<td>
								<a href="javascript: void(0);" class="editable" id="name" data-type="text" data-pk="{{ $category->id }}">
									{{ $category->name }}
								</a>
							</td>
							<td>
								<div class="btn-group">
									{{ HTML::link('forum/admin/delete-category/'. $category->id, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div>
				<a href="javascript: void();" class="btn btn-mini btn-primary" onclick="changeOrder();">Change the Category orders</a>
			</div>
		</div>
	</div>
</div>
@section('css')
	{{ HTML::style('/vendors/xEditable/bootstrap-editable/css/bootstrap-editable.css') }}
@endsection
@section('jsInclude')
	<script src="/vendors/jQuery/ui/js/jquery-ui-1.10.2.custom.min.js"></script>
	<script src="/vendors/xEditable/bootstrap-editable/js/bootstrap-editable.js"></script>
@stop
<script>
	@section('onReadyJs')
		// X-Editable details
		$.fn.editable.defaults.mode        = 'inline';
		$.fn.editable.defaults.url         = '/forum/admin/category-edit';
		$.fn.editable.defaults.showbuttons = false;
		$.fn.editable.defaults.class       = false;
		$('.editable').editable();

		// jQuery sortable for the order of the categories
		$(function() {
			$('#categories tbody').sortable({ helper: fixHelper, opacity: 0.6, cursor: 'move'}).disableSelection();
		});

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
	@stop
</script>
@section('js')
	<script>
		function changeOrder() {
			bootbox.confirm("Are you sure you want to continue?", "No", "Yes", function(confirmed) {
				if (confirmed) {
					// Submit the new order to the database
					var order = $('#categories tbody').sortable('serialize');
					$.post('/forum/admin/set-category-order', order, function(theResponse) {
						Messenger().post({message: theResponse});
					});
				}
			});
		}
	</script>
@stop