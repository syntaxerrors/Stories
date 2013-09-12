{{ Form::open(array('id' => 'personal', 'files' => true), 'post') }}
<div class="well">
    <div class="well-title">Site Theme</div>
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group" id="user_theme">
                <label class="control-label">Theme Color</label>
                <div class="controls">
                    {{ Form::select('user_theme', array(
                        'green' => 'Green',
                        'red' => 'Red',
                        'orange' => 'Orange',
                        'blue' => 'Blue',
                        'pink' => 'Pink',
                        'yellow' => 'Yellow'
                    ), array_pluck($activeUser->preferences, 'USER_THEME')) }}
                </div>
            </div>
        </div>
    </div>
    {{ Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'jsonSubmit')) }}
    <div id="message"></div>
</div>
{{ Form::close() }}

@section('js')
    <script>
        $('#personal').AjaxSubmit({
            path: '/{{ Request::path() }}',
            successMessage: 'Your profile has been updated.'}
        );
        $('#avatar').AjaxSubmit({
            path: '/user/avatar',
            successMessage: 'Your Avatar has been updated.'
        });
    </script>
@stop