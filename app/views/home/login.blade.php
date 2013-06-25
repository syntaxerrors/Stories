@if (Session::has('login_errors'))
	<div class="alert alert-error">Username or password incorrect.</div>
@endif

<div class="span4 offset5 well">
	<form class="form-signin" method="POST">
	<h2 class="form-signin-heading">Please sign in</h2>
	<input type="text" class="input-block-level" placeholder="Username" name="username">
	<input type="password" class="input-block-level" placeholder="Password" name="password">

	<label class="checkbox">
	  <input type="checkbox" value="remember-me" name="remember"> Remember me
	</label>
	<button class="btn btn-large btn-success" type="submit">Sign in</button>
	</form>
</div>