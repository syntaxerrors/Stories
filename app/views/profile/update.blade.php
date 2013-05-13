<div class="row-fluid">
	<div class="offset3 span6">
		<div class="well text-center">
			<div class="well-title">Update Profile</div>
				<?=Form::open_for_files()?>
					<?php
						$imageName = Str::classify($user->username) .'.png';
						if (file_exists( '/home/stygian/public_html/new_site2/public/img/avatars/'. $imageName)) {
							$image = HTML::image('img/avatars/'. $imageName, null);
							$type  = 'fileupload-exists';
						} else {
							$image = null;
							$type  = 'fileupload-new';
						}
					?>
					<div class="fileupload <?=$type?>" data-provides="fileupload" data-name="image">
						<div class="fileupload-new thumbnail text-center" style="width: 200px; height: 150px;">
							<?=HTML::image($user->gravitar)?>
						</div>
						<div class="fileupload-preview fileupload-exists thumbnail" style="line-height: 20px;"><?=$image?></div>
						<div>
							<span class="btn btn-file btn-primary"><span class="fileupload-new">Select avatar</span><span class="fileupload-exists">Change</span><input type="file" /></span>
							<a href="javascript: void();" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">Remove</a><br /><br />
							<a href="/profile/delete/<?=$user->id?>/avatar" class="btn fileupload-exists btn-danger">Remove Uploaded Image</a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="username"><small>Username</small></label>
						<div class="controls">
							<?=Form::text('username', $user->username, array('id' => 'username', 'placeholder' => 'Username'))?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="password"><small>Password<br /><small>(Leave blank to keep existing password)</small></small></label>
						<div class="controls">
							<?=Form::password('password', array('id' => 'password', 'placeholder' => 'New Password'))?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="firstName"><small>First Name</small></label>
						<div class="controls">
							<?=Form::text('firstName', $user->firstName, array('id' => 'firstName', 'placeholder' => 'First Name'))?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="lastName"><small>Last Name</small></label>
						<div class="controls">
							<?=Form::text('lastName', $user->lastName, array('id' => 'lastName', 'placeholder' => 'Last Name'))?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email"><small>Email</small></label>
						<div class="controls">
							<?=Form::text('email', $user->email, array('id' => 'email', 'placeholder' => 'Email'))?>
						</div>
					</div>
					<div class="controls">
						<?=Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary'))?>
						<?=Form::submit('Submit', array('class' => 'btn btn-small btn-primary'))?>
					</div>
				<?=Form::close();?>
			</div>
		</div>
	</div>
</div>