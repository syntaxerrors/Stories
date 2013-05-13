<div class="row-fluid">
    <div class="offset3 span6">
        <div class="well">
            <div class="well-title">Login</div>
            <?=Form::open(array('class' => 'form-horizontal'))?>
                <div class="control-group">
                    <label class="control-label" for="username">Username</label>
                    <div class="controls">
                        <?=Form::text('username', null, array('id' => 'username', 'required' => 'required'))?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password">Password</label>
                    <div class="controls">
                        <?=Form::password('password', array('id' => 'password', 'required' => 'required'))?>
                    </div>
                </div>
                <div class="controls">
                    <?=Form::submit('Login', array('class' => 'btn btn-small btn-primary'))?>
                </div>
            <?=Form::close()?>
        </div>
    </div>
</div>