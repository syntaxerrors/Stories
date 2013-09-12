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

{{ Form::open(array('id' => 'avatarForm', 'files' => true)) }}
<!-- { Form::open(array('action' => 'UserController@postAvatar','files' => true)) } -->
<div class="well" id="avatarWell">
    <div class="well-title">Avatar</div>
    <div class="row-fluid">
        <div class="span12 text-center">
            This site uses <a href="http://www.gravatar.com">Gravatar</a>. If you do not upload an avatar we will attempt to load your gravatar via your email address provided.
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group" id="gravatarEmail">
                <label class="control-label">Current Avatar</label>
                <div class="controls">
                    <img src="{{ $activeUser->gravitar }} ">
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group" id="upload">
                <label class="control-label">Upload an avatar</label>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" /></div>
                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                    <div>
                        <span class="btn btn-file btn-primary">
                            <span class="fileupload-new">Select image</span>
                            <span class="fileupload-exists">Change</span>
                            <!--<input type="file" name="avatar"/>-->
                            {{ Form::file('file') }}
                        </span>
                        <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">Remove</a>
                    </div>
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
        $('#personal').AjaxSubmit({
            path:'/{{ Request::path() }}',
            successMessage:'Your profile has been updated.'});
        
        $('#avatarForm').AjaxSubmit({
            path: '/user/avatar',
            successMessage: 'Your Avatar has been updated.'},
            function (data) {
                // redirect avatarWell to avatarCrop page.
            });

        $('.fileupload').fileupload();
    </script>
@stop