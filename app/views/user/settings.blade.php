<div class="row-fluid">
    <div class="span3">
        <div class="well">
            <div class="well-title">{{ $activeUser->username }}</div>
            <ul class="nav nav-list">
              <li class="active"><a href="">Profile</a></li>
              <li><a href="">Settings</a></li>
            </ul>
        </div>
    </div>
    <div class="span9">
        <div id="ajaxContent"></div>
        <div class="well">
            <div class="well-title">Personal Profile</div>
            <div class="row-fluid">
                <div class="span6">
                    <label><b>Display name</b></label>
                    {{ Form::text('displayName', $activeUser->username." !!!Create the column!!!", array('class' => 'input-block-level', 'placeholder' => 'How a stranger should greet you.')) }}
                    <br />

                    <label><b>First Name</b></label>
                    {{ Form::text('firstName', $activeUser->firstName, array('class' => 'input-block-level', 'placeholder' => 'The goofy name your mom gave you.')) }}
                    <br />

                    <label><b>Last name</b></label>
                    {{ Form::text('lastName', $activeUser->lastName, array('class' => 'input-block-level', 'placeholder' => 'The name you almost never hear.')) }}
                    <br />
                </div>
                <div class="span6">
                    <label><b>Email Address</b></label>
                    {{ Form::text('email', $activeUser->email, array('class' => 'input-block-level', 'placeholder' => 'Your email address.')) }}
                    <br />

                    <label><b>Location</b></label>
                    {{ Form::text('location', "!!!Create the column!!!", array('class' => 'input-block-level', 'placeholder' => 'Where you live?')) }}
                    <br />

                    <label><b>URL</b></label>
                    {{ Form::text('url', "!!!Create the column!!!", array('class' => 'input-block-level', 'placeholder' => 'URL of your site.')) }}
                    <br />
                </div>
            </div> 
        </div>

        <div class="well">
            <div class="well-title">Avatar</div>
            <div class="row-fluid">
                <div class="span6">
                    Update Form
                </div>
                <div class="span6">
                    <label><b>Gravatar Email (Private)</b></label>
                    {{ Form::text('gravatar', $activeUser->email." !!!Create the column!!!", array('class' => 'input-block-level', 'placeholder' => 'Get your avatar from gravatar.')) }}
                </div>
            </div> 
        </div>
    </div>
</div>

