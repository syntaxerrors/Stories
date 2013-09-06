{{ Form::open(array('id' => 'submitForm', 'files' => true), 'post') }}
<div class="well">
    <div class="well-title">User Password</div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group" id="oldPassword">
                <label class="control-label">Old Password</label>
                <div class="controls">
                    {{ Form::password('oldPassword', array('class' => 'input-block-level', 'placeholder' => 'Your old password')) }}
                </div>
            </div>
            <div class="control-group" id="newPassword">
                <label class="control-label">New Password </label>
                <div class="controls">
                    {{ Form::password('newPassword', array('class' => 'input-block-level', 'placeholder' => 'Your new password')) }}
                </div>
            </div>
            <div class="control-group" id="newPasswordAgain">
                <label class="control-label">Confirm New Password</label>
                <div class="controls">
                    {{ Form::password('newPasswordAgain', array('class' => 'input-block-level', 'placeholder' => 'Your new password again')) }}
                </div>
            </div>
        </div>
        <br />
    </div>
    <br />

    {{ Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'jsonSubmit')) }}
    <div id="message"></div>
</div>
{{ Form::close() }}
@section('js')
    <script>
        $('#submitForm').AjaxSubmit('/{{ Request::path() }}', 'Your password has been updated.');
    </script>
@stop