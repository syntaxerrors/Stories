<div class="row-fluid">
	<div class="span2">
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Main</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="users">Users</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="roleusers">Role Users</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="roles">Roles</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="actionroles">Action Roles</a></li>
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
		<div id="ajaxContent">
			Loading
		</div>
	</div>
</div>

<script>
	@section('onReadyJs')
		$.AjaxLeftTabs('/admin/', 'users');
	@endsection
</script>