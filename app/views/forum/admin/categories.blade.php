<div class="row-fluid">
	<div class="span12">
		<div class="well">
			<div class="well-title">Forum Categories</div>
			<table class="table table-condensed table-striped table-hover" id="sortCategories">
				<thead>
					<tr>
						<th style="width: 2%;">&nbsp;</th>
						<th style="width: 49%;">Name</th>
						<th style="width: 49%;">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
						<tr id="{{ $category->id }}">
							<td style="cursor: move;"><i class="icon-resize-vertical" title="Change order"></i></td>
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
		</div>
	</div>
</div>
@section('css')
	{{ HTML::style('/vendors/xEditable/bootstrap-editable/css/bootstrap-editable.css') }}
@endsection
@section('jsInclude')
	{{ HTML::script('/vendors/tableDnD/jquery.tablednd.js') }}
	{{ HTML::script('/vendors/xEditable/bootstrap-editable/js/bootstrap-editable.js') }}
@stop
<script>
	@section('onReadyJs')
		// X-Editable details
		$.fn.editable.defaults.mode        = 'inline';
		$.fn.editable.defaults.url         = '/forum/admin/category-edit';
		$.fn.editable.defaults.showbuttons = false;
		$.fn.editable.defaults.class       = false;
		$('.editable').editable();

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		}

		$('#sortCategories').tableDnD({
			onDragClass: 'primary',
			dragHandle: '.icon-resize-vertical',
			onDrop: function(table, row) {
				$.post('/forum/admin/move-categories', $.tableDnD.serialize());
			}
		});
	@stop
</script>