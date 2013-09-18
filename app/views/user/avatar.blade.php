{{ HTML::style('vendor/bootstrap-switch/static/stylesheets/bootstrap-switch.css')}}

{{ Form::open(array('id' => 'avatarForm', 'files' => true)) }}
<!-- { Form::open(array('action' => 'UserController@postAvatar','files' => true)) } -->
<div class="well" id="avatarWell">
    <div class="well-title">Avatar</div>
    <div class="row-fluid">
        <div class="span12 text-center">
            This site uses <a href="http://www.gravatar.com" target="_blank">Gravatar</a>. If you do not upload an avatar we will attempt to load your gravatar via your email address provided.
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group" style="display: inline-block;">
                <label class="control-label">Current Avatar</label>
                <div class="controls">
                    {{ HTML::image($activeUser->gravitar, null, array('class'=> 'media-object pull-left', 'style' => 'width: 150px;')) }}
                </div>
            </div>
            <div class="control-group" style="display: inline-block;">
                <label class="control-label">Current Gravatar</label>
                <div class="controls">
                    {{ HTML::image($activeUser->onlyGravatar, null, array('class'=> 'media-object pull-left', 'style' => 'width: 150px;')) }}
                </div>
            </div>
            <div class="control-group">
                <div id="switch" class="controls">
                    <div id="animated-switch" class="make-switch" data-animated="true">
                        <input type="checkbox" checked>
                    </div>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group" id="upload">
                <label class="control-label">Upload an avatar</label>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 150px; height: 150px;"><img src="http://www.placehold.it/150x150/EFEFEF/AAAAAA&text=no+image" /></div>
                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
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
    </div>

    {{ Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'jsonSubmit')) }}
    <div id="avatarMessage"></div>
</div>
{{ Form::close() }}

@section('jsInclude')
    {{ HTML::script('vendor/bootstrap-switch/static/js/bootstrap-switch.min.js') }}
@stop

@section('js')
    <script>
        $('#animated-switch').bootstrapSwitch();
    </script>
@stop

<script>
    @section('onReadyJs')
        $('form#avatarForm').ajaxForm({
            url: '/user/avatar',
            target: '#avatarMessage',
            success: function() {
                alert('Submitted');
                return false;
            }
        });
    @endsection
</script>