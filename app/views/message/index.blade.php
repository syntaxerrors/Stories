@section('css')
	{{ HTML::style('/vendors/fuelux/dist/css/fuelux.css') }}
@stop
<div class="row-fluid">
	<div class="span3 fuelux">
		<div id="MyTree" class="tree">
			<div class = "tree-folder" style="display:none;">
				<div class="tree-folder-header">
					<i class="icon-folder-close text-info"></i>
					<div class="tree-folder-name"></div>
				</div>
				<div class="tree-folder-content"></div>
				<div class="tree-loader" style="display:none"></div>
			</div>
			<div class="tree-item" style="display:none;">
				<i class="tree-dot text-info"></i>
				<div class="tree-item-name"></div>
			</div>
		</div>
	</div>
</div>
<?=pp($folders->toJson())?>
@section('jsInclude')
	{{ HTML::script('/vendors/fuelux/dist/loader.js') }}
	{{ HTML::script('/vendors/fuelux/sample/datasourceTree.js') }}
@stop
@section('js')
	<script> 	
		$(function () {
			// Connect to the ersatz service
			ersatz = new Ersatz({
				server: serviceAddress,
				event: handleEvent,
				count: handleCount,
				error: handleError,
				postEvent: postEvent,
				subscriptions: groups.concat(users),
				countSubscriptions: counts
				countSubscriptions: counts.concat()
			});

			// Create the monitoring table
			if (monitoringDetailValue == 1) {
				var newTable = new Monitoring(locationMonitoringCounts);

				$('#monitoring').append(newTable.monitoringHtml());
			}

			// Create a table for each group
			$.each(groupNames, function(lowerTitle,displayName) {
				var newTable = new Table('dashboard_ticketgroup_'+ lowerTitle, displayName);
				// Monitoring is a special case.  Skip it for normal group tables
				if (lowerTitle != 'monitoring') {
					var newTable = new Table('dashboard_ticketgroup_'+ lowerTitle, displayName);
				$("#ticketGroups").append(newTable.groupHtml());
					$("#ticketGroups").append(newTable.groupHtml());
				}
			});

			// Create a table for each employee
			$.each(users, function(key,subKey) {
				var assignedEmployeeId	   = subKey.split(':')[1];
				var assignedEmployeeUsername = subKey.split(':')[2];
				var newTable				 = new Table('dashboard_assignedemployee_'+ assignedEmployeeUsername, userNames[subKey]);

				$("#assignedEmployee").append(newTable.employeeHtml());

				if (assignedEmployeeId == activeUserId) {
					$('#dashboard_assignedemployee_'+ assignedEmployeeUsername).show();
				}
			});

			// Setup the interval to update yellow and update times
			setInterval(refreshUpdateTimes, 2000);

			// Set up the active user ticket actuivity
			$('#dashboard_assignedemployee_'+ activeUserName +'_activity').load('getActiveUserTicketActivity/<?=$activeUser->id?>?layout=null');
			setInterval(function(index) {
				$('#dashboard_assignedemployee_'+ activeUserName +'_activity').load('getActiveUserTicketActivity/<?=$activeUser->id?>?layout=null');
			}, 60000);
		});
	</script>
@stop