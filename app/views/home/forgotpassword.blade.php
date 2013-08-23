<div class="row-fluid">
	<div class="offset3 span6">
		<div class="well">
			<div class="well-title">Forgot Password</div>
			{{ Form::open(array('class' => 'form-horizontal')) }}
				<div class="control-group">
					<label class="control-label" for="email">Email</label>
					<div class="controls">
						{{ Form::email('email', null, array('id' => 'email', 'required' => 'required')) }}
					</div>
				</div>
				<div class="controls">
					{{ Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Send new password', array('class' => 'btn btn-small btn-primary')) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>