<div class="row-fluid">
	<div class="span2">
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Permissions</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="users">Users</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="roleusers">Role Users</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="roles">Roles</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="actionroles">Action Roles</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="actions">Actions</a></li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">General</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="theme">Theme</a></li>
		</ul>
		@if ($gameMode)
			<ul class="nav nav-tabs nav-stacked">
				<li class="nav-title">Games</li>
				<li><a href="javascript: void(0);" class="ajaxLink" id="gameconfigs">Game Configs</a></li>
			</ul>
		@endif
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Class Types</li>
			<li class="nav-header">Messages</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="messagetypes">Message Types</a></li>
			@if ($gameMode)
				<li class="nav-header">Games</li>
				<li><a href="javascript: void(0);" class="ajaxLink" id="gametypes">Game Types</a></li>
			@endif
			<li class="nav-header">Forums</li>
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