<div class="row-fluid">
	<div class="offset3 span6">
		<div class="well">
			<div class="well-title">{{ $user->username }}'s Profile</div>
			<div class="media">
				{{ HTML::image($user->gravitar, null, array('class'=> 'media-object pull-left', 'style' => 'width: 100px;')) }}
				<div class="media-body">
					<h4 class="media-heading">
						User Details
						@if ($user->id == $activeUser->id)
							<div class="pull-right">
								{{ HTML::link('profile/update/'. $user->id, 'Edit') }}
							</div>
						@endif
					</h4>
	    			<table class="table table-hover table-condensed">
	    				<tbody>
	    					<tr>
	    						<td style="width: 100px;"><strong>Username:</strong></td>
	    						<td>{{ $user->username }}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>Full Name:</strong></td>
	    						<td>{{ $user->fullName }}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>Email:</strong></td>
	    						<td>{{ HTML::mailto($user->email) }}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>Join Date:</strong></td>
	    						<td>{{ date('F jS, Y \a\t h:ia', strtotime($user->created_at)) }}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>Last Active:</strong></td>
	    						<td>{{ $user->lastActiveReadable }}</td>
	    					</tr>
	    					<tr>
	    						<td><strong>Status:</strong></td>
	    						<td>
				            		{{ ($user->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes'))
				                		? HTML::image('img/icons/online.png', 'Online', array('title' => 'Online')) .' Online'
				                		: HTML::image('img/icons/offline.png', 'Offline', array('title' => 'Offline')) .' Offline'
				                	) }}
				                </td>
	    					</tr>
	    				</tbody>
	    			</table>
	    		</div>
			</div>
		</div>
	</div>
</div>
<div class="well">
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#forumCharacters" data-toggle="tab">Forum Characters</a></li>
			<li><a href="#forumStats" data-toggle="tab">Forum Stats</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="forumCharacters">
				@if (count($user->characters) > 0)
					@foreach ($user->characters as $character)
						@if ($character->npcFlag == 0)
							@include('profile.forumcharacterbox')
						@endif
					@endforeach
				@endif
			</div>
			<div class="tab-pane fade" id="forumStats">
				@if ($user->postsCount > 0)
					@include('profile.forumstatsbox')
				@endif
			</div>
		</div>
	</div>
</div>