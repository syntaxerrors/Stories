<div class="row-fluid">
	<div class="span12" id="listPanel">
		<div class="well well-small">
			<div class="well-title">
				{{ $settings->title }}
				<div class="well-btn well-btn-right">
					<a href="javascript: void(0);" onClick="addPanel()"><i class="icon-plus-sign"></i></a>
				</div>
			</div>
			<table class="table table-hover table-striped table-condensed" id="dataTable">
				<thead>
					<tr>
						<th style="display: none;"></th>
						@foreach ($settings->displayFields as $key => $details)
							<th class="text-left">{{ ucwords(str_replace('_', ' ', $key)) }}</th>
						@endforeach
						<th class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					@if (count($settings->resources) > 0)
						@foreach ($settings->resources as $resource)
							<tr data-sort="{{ $resource->{$settings->sortProperty} }}">
								<td style="display: none;">
									<input type="hidden"
										id="{{ $resource->id }}"
										@foreach ($settings->formFields as $key => $details)
											data-{{ $key }}="{{ $resource->{$key} }}"
										@endforeach
									 />
								</td>
								@foreach ($settings->displayFields as $key => $details)
									@if (isset($details->linkLocation) && $details->linkLocation != null)
										@if ($details->linkLocation == 'mailto')
											<td>{{ HTML::mailto($resource->email, HTML::email($resource->email)) }}</td>
										@else
											<td>
												{{ HTML::link($details->linkLocation . (isset($details->linkProperty) ? $resource->{$details->linkProperty} : null), ucwords($resource->{$key}))}}
											</td>
										@endif
									@else
										<td>{{ ucwords($resource->{$key}) }}</td>
									@endif
								@endforeach
								<td class="text-center">
									<div class="btn-group">
										@if (is_int($resource->id))
											<a href="javascript:void(0)" class="btn btn-mini btn-primary" onClick="editDetails({{ $resource->id }});">Edit</a>
										@else
											<a href="javascript:void(0)" class="btn btn-mini btn-primary" onClick="editDetails('{{ $resource->id }}');">Edit</a>
										@endif
										@if (!isset($settings->deleteFlag) || $settings->deleteFlag == true)
											{{ HTML::link($settings->deleteLink . $resource->{$settings->deleteProperty}, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
										@endif
									</div>
								</td>
							</tr>
						@endforeach
					@else
						<tr id="placeholder">
							<td colspan="30">No {{ strtolower($settings->title) }} have been added.</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	@include('helpers.crud.formfields')
</div>
@include('helpers.helpModal')

@section('jsInclude')
	{{ HTML::script('/vendors/jansyBootstrap/js/jasny-bootstrap.min.js') }}
	{{ HTML::script('/js/crud.js') }}
@stop

@section('js')
	<script>
		var settings = {{ json_encode($settings) }};
		var rootPath = '{{ Request::root() }}';

		$('#submitForm').AjaxSubmit(
			{
				path: '/{{ Request::path() }}',
				successMessage: 'Entry successfully updated.'
			},
			function(data) {
				$.Crud(
					{
						type: 'normal',
						data: data,
						settings: settings,
						rootPath: rootPath,
					},
					function(results) {
						// We are creating a new row
						if ($('#id').val() == '') {
							// Remove any existing placeholder
							$('#placeholder').remove();

							// Add the new row
							$('#dataTable tbody').append(results.newRow);

							// Reorder all the table rows
							entrySort();
						} else {
							// Get the existing row to edit
							var row = $('input#'+ resource.id).closest('tr');

							// Add the columns to the row
							row.empty().append(results.newColumns);
						}
						$('#submitForm')[0].reset();
					}
				);
			}
		);

		function editDetails(objectId) {
			// Reset the form
			$('#submitForm')[0].reset();
			$('#submitForm .error').removeClass('error');
			$('#submitForm #message').empty();

			var object = $('#'+ objectId);
			$('#id').val(objectId);

			$.each(settings.formFields, function(key, details) {
				$('#input_'+ key).val(object.attr('data-'+ key));
			});

			$('#listPanel').removeClass('span12').addClass('span8');
			$('.span4').show();
		}

		function addPanel() {
			$('#listPanel').toggleClass('span12').toggleClass('span8');
			if ($('.span4').css('display') == 'none') {
				$('.span4').show();
			} else {
				$('.span4').hide();
				$('#submitForm')[0].reset();
				$('#submitForm .error').removeClass('error');
				$('#submitForm #message').empty();
			}
		}

		function entrySort() {
			$('#dataTable tbody').children('tr').sort(function(a, b) {
				var upA = $(a).attr('data-sort').toUpperCase();
				var upB = $(b).attr('data-sort').toUpperCase();
				return (upA < upB) ? -1 : (upA > upB) ? 1 : 0;
			}).appendTo('#dataTable tbody');
		}

		function ucwords(string) {
			string = string.toLowerCase().replace(/\b[a-z]/g, function(letter) {
				return letter.toUpperCase();
			});
			return string;
		}
	</script>
@stop