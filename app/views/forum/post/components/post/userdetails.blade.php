			    			<table class="table table-hover table-condensed">
			    				<caption>User Details</caption>
			    				<tbody>
			    					<tr>
			    						<td style="width: 100px;"><strong>Username:</strong></td>
			    						<td>{{ $post->author->username }}</td>
			    					</tr>
			    					<tr>
			    						<td><strong>Full Name:</strong></td>
			    						<td>{{ $post->author->fullName }}</td>
			    					</tr>
			    					<tr>
			    						<td><strong>Join Date:</strong></td>
			    						<td>{{ date('F jS, Y \a\t h:ia', strtotime($post->author->created_at)) }}</td>
			    					</tr>
			    					<tr>
			    						<td><strong>Last Active:</strong></td>
			    						<td>{{ $post->author->lastActiveReadable }}</td>
			    					</tr>
			    				</tbody>
			    			</table>