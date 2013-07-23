@section('css')
	{{ HTML::style('/vendors/fuelux/dist/css/fuelux.css') }}
@stop
<div class="row-fluid">
	<div class="offset1 span10 fuelux">
		<div id="MyWizard" class="wizard">
			<ul class="steps">
				<li data-target="#step1" class="active"><span class="badge badge-info">1</span>Step 1<span class="chevron"></span></li>
				<li data-target="#step2"><span class="badge">2</span>Step 2<span class="chevron"></span></li>
				<li data-target="#step3"><span class="badge">3</span>Step 3<span class="chevron"></span></li>
				<li data-target="#step4"><span class="badge">4</span>Step 4<span class="chevron"></span></li>
				<li data-target="#step5"><span class="badge">5</span>Step 5<span class="chevron"></span></li>
			</ul>
			<div class="actions">
				<button type="button" class="btn btn-mini btn-primary btn-prev"> <i class="icon-arrow-left"></i>Prev</button>
				<button type="button" class="btn btn-mini btn-next" data-last="Finish">Next<i class="icon-arrow-right"></i></button>
			</div>
		</div>
		<div class="step-content">
			<div class="step-pane active" id="step1">This is step 1</div>
			<div class="step-pane" id="step2">This is step 2</div>
			<div class="step-pane" id="step3">This is step 3</div>
			<div class="step-pane" id="step4">This is step 4</div>
			<div class="step-pane" id="step5">This is step 5</div>
		</div>
	</div>
</div>
@section('jsInclude')
	<script src="/vendors/fuelux/dist/loader.min.js"></script>
	<!--<script src="/vendors/fuelux/src/wizard.js"></script>-->
@stop
@section('js')
	<script>
		$('#MyWizard').wizard();
			// $('#MyWizard').on('change', function(e, data) {
			// 	console.log('change');
			// 	if(data.step===3 && data.direction==='next') {
			// 		// return e.preventDefault();
			// 	}
			// });
			// $('#MyWizard').on('changed', function(e, data) {
			// 	console.log('changed');
			// });
			// $('#MyWizard').on('finished', function(e, data) {
			// 	console.log('finished');
			// });
			// $('#btnWizardPrev').on('click', function() {
			// 	$('#MyWizard').wizard('previous');
			// });
			// $('#btnWizardNext').on('click', function() {
			// 	$('#MyWizard').wizard('next','foo');
			// });
			// $('#btnWizardStep').on('click', function() {
			// 	var item = $('#MyWizard').wizard('selectedItem');
			// 	console.log(item.step);
			// });
			// $('#MyWizard').on('stepclick', function(e, data) {
			// 	console.log('step' + data.step + ' clicked');
			// 	if(data.step===1) {
			// 		// return e.preventDefault();
			// 	}
			// });
	</script>
@stop