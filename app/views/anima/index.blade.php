<div class="row-fluid">
	<div class="span2">
		<ul class="nav nav-tabs nav-stacked">
			<li class="nav-title">Main</li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="awaiting">Awaiting Approval</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="characters">Characters</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="entities">Entities</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="enemies">Enemies</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="hordes">Hordes</a></li>
			<li><a href="javascript: void(0);" class="ajaxLink" id="inactives">Inactive Characters</a></li>
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
		$.AjaxLeftTabs('/anima/', 'characters');
	@endsection
</script>