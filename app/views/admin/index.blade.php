<div class="row-fluid">
	<div class="span2">
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Main</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="users">Users</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="roles">Roles</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="actions">Actions</a></li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">AH Details</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="series">Series</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="games">Games</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="members">Members</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="teams">Teams</a></li>
		</ul>
	</div>
	<div class="span10">
		<div id="ajaxContent"></div>
	</div>
</div>

<script>
	@section('onReadyJs')
		var url   = location.href;
		var parts = url.split('#');

		if (parts[1] != null) {
			$('#'+ parts[1]).parent().addClass('active');
			$('#ajaxContent').load('/admin/'+ parts[1]);
		} else {
			$('#users').parent().addClass('active');
			$('#ajaxContent').load('/admin/users');
		}
		$('.ajaxLink').click(function() {

			$('.ajaxLink').parent().removeClass('active');
			$(this).parent().addClass('active');

			var link = $(this).attr('id');
			$('#ajaxContent').load('/admin/'+ link);
		});
	@endsection
</script>