<div class="row-fluid">
	<div class="offset3 span6">
		<div class="well">
			<div class="well-title">Register</div>
			{{ Form::open(array('class' => 'form-horizontal')) }}
				<div class="control-group">
					<label class="control-label" for="username">Username</label>
					<div class="controls">
						{{ Form::text('username', null, array('id' => 'username', 'required' => 'required')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password">Password</label>
					<div class="controls">
						{{ Form::password('password', array('id' => 'password', 'required' => 'required')) }}
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email">Email</label>
					<div class="controls">
						{{ Form::email('email', null, array('id' => 'email', 'required' => 'required')) }}
					</div>
				</div>
				<div class="controls">
					{{ Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Complete Registration', array('class' => 'btn btn-small btn-primary')) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>