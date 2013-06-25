<div class="row-fluid">
	<div class="span4">
		<div class="well">
			<div class="well-title">Options</div>
			<div class="row-fluid text-center">
				<div class="span4"><button type="button" class="btn btn-primary" value="LETS_PLAY" data-toggle="button">Lets Play</button></div>
				<div class="span4"><button type="button" class="btn btn-primary" value="VERSUS" data-toggle="button">Versus</button></div>
				<div class="span4"><button type="button" class="btn btn-primary" value="THINGS_TO_DO_IN" data-toggle="button">Things to do in</button></div>
			</div>
		</div>
	</div>
	<div class="span8">
		<div class="well">
			<div class="well-title">Scoreboard</div>
			<div id="scoreboard"></div>
		</div>
	</div>
</div>
<script>
@section('js')
	$('button').click(function() {
		$('#scoreboard').append(this.value);
	});
</script>