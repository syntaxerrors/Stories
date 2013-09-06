{{ Form::open(array('id' => 'personal', 'files' => true), 'post') }}
<div class="well">
    <div class="well-title">Personal Information</div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group" id="displayName">
                <label class="control-label">Display name</label>
                <div class="controls">
                    {{ Form::text('displayName', $activeUser->displayName, array('class' => 'input-block-level', 'placeholder' => 'How a stranger should greet you.')) }}
                </div>
            </div>
            <div class="control-group" id="firstName">
                <label class="control-label">First Name</label>
                <div class="controls">
                    {{ Form::text('firstName', $activeUser->firstName, array('class' => 'input-block-level', 'placeholder' => 'The goofy name your mom gave you.')) }}
                </div>
            </div>
            <div class="control-group" id="lastName">
                <label class="control-label">Last name</label>
                <div class="controls">
                    {{ Form::text('lastName', $activeUser->lastName, array('class' => 'input-block-level', 'placeholder' => 'The name you almost never hear.')) }}
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group" id="email">
                <label class="control-label">Email Address</label>
                <div class="controls">
                    {{ Form::text('email', $activeUser->email, array('class' => 'input-block-level', 'placeholder' => 'Your email address.', 'required' => 'required')) }}
                </div>
            </div>
            <div class="control-group" id="location">
                <label class="control-label">Location</label>
                <div class="controls">
                    {{ Form::text('location', $activeUser->location, array('class' => 'input-block-level', 'placeholder' => 'Where you live?')) }}
                </div>
            </div>
            <div class="control-group" id="url">
                <label class="control-label">URL</label>
                <div class="controls">
                    {{ Form::text('url', $activeUser->url, array('class' => 'input-block-level', 'placeholder' => 'URL of your site.')) }}
                </div>
            </div>
        </div>
        <br />
    </div>

    {{ Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'jsonSubmit')) }}
    <div id="message"></div>
</div>
{{ Form::close() }}

{{ Form::open(array('id' => 'avatar', 'files' => true), 'post') }}
<div class="well">
    <div class="well-title">Avatar</div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group" id="displayName">
                <label class="control-label">Remote Location</label>
                <div class="controls">
                    {{ Form::text('displayName', $activeUser->displayName, array('class' => 'input-block-level', 'placeholder' => 'How a stranger should greet you.')) }}
                </div>
            </div>
            <div class="control-group" id="firstName">
                <label class="control-label">Gravatar Email Address</label>
                <div class="controls">
                    {{ Form::text('firstName', $activeUser->gravatar, array('class' => 'input-block-level', 'placeholder' => 'Leave blank to use your personal email address')) }}
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group" id="email">
                <label class="control-label">Upload</label>
                <div class="controls">
                    {{ Form::text('email', $activeUser->email, array('class' => 'input-block-level', 'placeholder' => 'Your email address.', 'required' => 'required')) }}
                </div>
            </div>
        </div>
        <br />
    </div>

    {{ Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'jsonSubmit')) }}
    <div id="message"></div>
</div>
{{ Form::close() }}

@section('js')
    <script>
        $('#personal').AjaxSubmit('/{{ Request::path() }}', 'Your profile has been updated.');
        $('#avatar').AjaxSubmit('/user/avatar', 'Your Avatar has been updated.');
    </script>
@stop