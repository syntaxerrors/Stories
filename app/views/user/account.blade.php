<div class="row-fluid">
    <div class="span2">
        <ul class="nav nav-tabs nav-stacked">
            <li class="nav-title"> {{ $activeUser->username }} </li>
            <li><a href="javascript: void(0);" class="ajaxLink" id="profile">Profile</a></li>
            <li><a href="javascript: void(0);" class="ajaxLink" id="avatar">Avatar</a></li>
            <li><a href="javascript: void(0);" class="ajaxLink" id="preferences">Preferences</a></li>
            <li><a href="javascript: void(0);" class="ajaxLink" id="password">Change Password</a></li>
            <li><a href="javascript: void(0);" class="ajaxLink" id="theme">Change Theme</a></li>
            <li><a href="javascript: void(0);" class="ajaxLink" id="rules">Message Rules</a></li>
        </ul>
    </div>
    <div class="span10">
        <div id="ajaxContent">
            Loading
        </div>
    </div>
</div>

@section('jsInclude')
    {{ HTML::script('vendor/form/jquery.form.js') }}
@stop

<script>
    @section('onReadyJs')
        $.AjaxLeftTabs('/user/', 'profile');
    @endsection
</script>