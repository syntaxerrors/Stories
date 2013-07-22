<div class="row-fluid">
	<div class="span2"><a href="awaiting" class="btn btn-mini btn-primary">Awaiting Approval</a></div>
	<div class="span2"><a href="characters" class="btn btn-mini btn-primary">Characters</a></div>
	<div class="span2"><a href="entities" class="btn btn-mini btn-primary">Entities</a></div>
	<div class="span2"><a href="enemies" class="btn btn-mini btn-primary">Enemies</a></div>
	<div class="span2"><a href="hordes" class="btn btn-mini btn-primary">Hordes</a></div>
	<div class="span2"><a href="inactives" class="btn btn-mini btn-primary">Inactive Characters</a></div>
</div>
<div class="row-fluid">
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