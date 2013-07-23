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
			<li class="nav-title">Games</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="gameconfigs">Game Configs</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="gametypes">Game Types</a></li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Forums</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="categorytypes">Category Types</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="boardtypes">Board Types</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="posttypes">Post Types</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="replytypes">Reply Types</a></li>
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