@if(Session::has('message'))
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert" href="#">×</a>
		<strong>Well done!</strong>
		{{ Session::get('message') }}
	</div>
@endif

@if(count($errors->getMessageBag()) > 0)
	<div class="alert alert-error">
		<a class="close" data-dismiss="alert" href="#">×</a>
		<h4 class="alert-heading">Oh snap! </h4>
		<ul>
			@foreach ($errors->getMessageBag() as $error)
				<li> {{ $error[0] }} </li>
			@endforeach
		</ul>
	</div>
@endif
<div class="span4 offset5 well">
	{{ Form::open(array('url' => '/registration'), 'POST') }}
		<h2 class="form-signin-heading">Registration</h2>
		{{ Form::text('username', Input::old('username'), array('class' => 'input-block-level', 'placeholder' => 'Username')) }} <br />
		{{ Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Password')) }} <br />
		{{ Form::text('email', Input::old('email'), array('class' => 'input-block-level', 'placeholder' => 'Email Address')) }} <br />
		<button class="btn btn-large btn-primary" type="submit">Sign up its FREE</button>
	</form>
</div>