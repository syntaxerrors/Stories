<div class="row-fluid">
	<div class="span2">
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Moderation</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="escalated-posts">
					Escalated Posts
					<span class="badge badge-important pull-right">{{ ($escalatedPostsCount > 0 ? $escalatedPostsCount : null) }}</span>
				</a>
			</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="report-logs">
					Report Logs
					<span class="badge pull-right">{{ $reportLogsCount }}</span>
				</a>
			</li>
		</ul>
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Administration</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="users">
					Users
					<span class="badge pull-right">{{ $userCount }}</span>
				</a>
			</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="categories">
					Categories
					<span class="badge pull-right">{{ $categoryCount }}</span>
				</a>
			</li>
			<li>
				<a href="javascript: void(0);" class="ajaxLink" id="boards">
					Boards
					<span class="badge pull-right">{{ $boardCount }}</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="span10">
		<div id="ajaxContent"></div>
	</div>
</div>
<script>
	@section('onReadyJs')
		$.AjaxLeftTabs('/forum/admin/', 'escalated-posts');
	@endsection
</script>