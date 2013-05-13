{{ Form::open(array('id' => 'form')) }}
<div class="main" style="width: 100px;">
	<table class="main">
		<tr>
			<td id="th_main">D3</td>
			<td>{{ Form::text('d3', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D4</td>
			<td>{{ Form::text('d4', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D6</td>
			<td>{{ Form::text('d6', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D8</td>
			<td>{{ Form::text('d8', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D10</td>
			<td>{{ Form::text('d10', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D12</td>
			<td>{{ Form::text('d12', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D20</td>
			<td>{{ Form::text('d20', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td id="th_main">D100</td>
			<td>{{ Form::text('d100', null, array('size' => 1)) }}</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><a href="javascript:void();" onClick="$.post('/home/dice', $('#form').serialize(), function(data) { $('#dice').html(data);});$('#form').find(':input').each(function() { $(this).val('') })">Roll!</a></td>
		</tr>
	</table>
	<div id="dice" class="center"></div>
</div>
{{ Form::close() }}