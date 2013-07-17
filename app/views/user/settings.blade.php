{{ Form::open() }}
    <div class="well">
        <div class="well-title">Change Password</div>
        <div class="row-fluid">
            <div class="offset3 span5">
                <label><b>Old Password</b></label>
                {{ Form::text('oldPassword', null, array('class' => 'input-xxlarge', 'placeholder' => 'old \'n busted')) }}
                <br />

                <label><b>New Password</b></label>
                {{ Form::text('newPassword', null, array('class' => 'input-xxlarge', 'placeholder' => 'New Hotness')) }}
                <br />

                <label><b>Confirm New Password</b></label>
                {{ Form::text('confirmNewPassword', null, array('class' => 'input-xxlarge', 'placeholder' => 'Dubble New Hotness all the way!')) }}
                <br />
                
                {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
                <br />
            </div>
        </div> 
    </div>
{{ Form::close() }}