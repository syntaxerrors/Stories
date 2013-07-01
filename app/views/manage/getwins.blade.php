<table class="table table-condensed table-striped table-hover">
	<tbody>
		@if (count($episode->wins->winmorph->name) > 0)
			@foreach ($episode->wins->winmorph->name as $winner)
				<tr>
					<td>{{ $winner }}</td>
				</tr>
			@endforeach
		@else
			<tr>
				<td>No winners added.</td>
			</tr>
		@endif
	</tbody>
</table>
<div id="foot">
  <div class="modal-footer">
        <div class="btn-group">
			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
        </div>
  </div>
</div>
<script>
	$('#modal #myModalLabel').html("{{ $episode->title }} Winners");
	$('#modal .modal-footer').replaceWith($('#foot'));
</script>