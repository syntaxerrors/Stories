					    			<table class="table table-hover table-condensed">
					    				<caption>User Details</caption>
					    				<tbody>
					    					<tr>
					    						<td style="width: 100px;"><strong>Username:</strong></td>
					    						<td>{{ $reply->author->username }}</td>
					    					</tr>
					    					<tr>
					    						<td><strong>Full Name:</strong></td>
					    						<td>{{ $reply->author->fullName }}</td>
					    					</tr>
					    					<tr>
					    						<td><strong>Email:</strong></td>
					    						<td>{{ $reply->author->email }}</td>
					    					</tr>
					    					<tr>
					    						<td><strong>Join Date:</strong></td>
					    						<td>{{ date('F jS, Y _a_t h:ia', strtotime($reply->author->created_at)) }}</td>
					    					</tr>
					    					<tr>
					    						<td><strong>Last Active:</strong></td>
					    						<td>{{ $reply->author->lastActiveReadable }}</td>
					    					</tr>
					    				</tbody>
					    			</table>