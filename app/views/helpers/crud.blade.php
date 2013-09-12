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
							@if (isset($details->multi))
								<th class="text-left">{{ ucwords(str_replace('_', ' ', $details->multiTitle)) }}</th>
							@endif
						@endforeach
						<th class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					@if (count($resources) > 0)
						@foreach ($resources as $resource)
							<tr data-sort="{{ $resource->{$settings->sortProperty} }}">
								<td style="display: none;">
									<input type="hidden"
										id="{{ $resource->id }}"
										@if (isset($settings->multi) && $settings->multi == true)
											@foreach ($settings->multiData as $key => $details)
												data-{{ $key}}="{{ variableObject($resource, $details) }}"
											@endforeach
										@else
											@foreach ($settings->formFields as $key => $details)
												data-{{ $key }}="{{ $resource->{$key} }}"
											@endforeach
										@endif
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
									@elseif (isset($settings->multi) && $settings->multi == true)
										<?php
											$multi = variableObject($resource, $settings->multiObject);
										?>
										<td>{{ ucwords($resource->{$key}) }}</td>
										<td>{{ implode('<br />', $multi->toArray()) }}</td>
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
										@if (!isset($settings->noDelete))
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
	<div class="span4" style="display: none;">
		<div class="well text-center">
			<div class="well-title">
				Add/Update
				<div class="well-btn well-btn-right">
					<a href="javascript: void(0);" onClick="addPanel()"><i class="icon-remove"></i></a>
				</div>
			</div>
			{{ Form::open(array('id' => 'submitForm', 'files' => true)) }}
				<div class="control-group">
					<div class="controls">
						{{ Form::text('id', null, array('id' => 'id', 'readonly' => 'readonly', 'placeholder' => 'Existing Id', 'style' => 'margin-left: 14px;')) }}
						<a href="#helpModal" data-toggle="modal" class="icon-white icon-question-sign"></a>
					</div>
				</div>
				@foreach ($settings->formFields as $key => $details)
					@if ($details->field == 'image')
						<div class="fileupload fileupload-new" data-provides="fileupload" data-name="image">
							<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
								{{ HTML::image('img/noImage.gif', null, array('style' => 'width: 200px;')) }}
							</div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="line-height: 20px;"></div>
							<div>
								<span class="btn btn-file btn-primary">
									<span class="fileupload-new">Select image</span>
									<span class="fileupload-exists">Change</span>
									<input id="image" type="file" />
								</span>
								<a href="javascript: void(0);" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">Remove</a>
							</div>
						</div>
					@endif
					<div class="control-group" id="{{ $key }}">
						<div class="controls">
							@if ($details->field == 'text')
								{{ Form::text($key, null, array('id' => 'input_'. $key, 'placeholder' => (isset($details->placeholder) ? $details->placeholder : ucwords($key) ))) }}
							@elseif ($details->field == 'email')
								{{ Form::email($key, null, array('id' => 'input_'. $key, 'placeholder' => (isset($details->placeholder) ? $details->placeholder : ucwords($key) ))) }}
							@elseif ($details->field == 'textarea')
								{{ Form::textarea($key, null, array('id' => 'input_'. $key, 'placeholder' => (isset($details->placeholder) ? $details->placeholder : ucwords($key) ))) }}
							@elseif ($details->field == 'select')
								{{ Form::select($key, $details->selectArray, null, array('id' => 'input_'. $key)) }}
							@elseif ($details->field == 'multiselect')
								{{ Form::select($key .'[]', $details->selectArray, null, array('id' => 'input_'. $key, 'multiple' => 'multiple')) }}
							@endif
						</div>
					</div>
				@endforeach
				<div class="controls">
					{{ Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary', 'id' => 'jsonSubmit')) }}
				</div>
				<div id="message"></div>
			{{ Form::close(); }}
		</div>
	</div>
</div>
@include('helpers.helpModal')

@section('jsInclude')
	{{ HTML::script('/vendors/jansyBootstrap/js/jasny-bootstrap.min.js')}}
@stop

@section('js')
	<script>
		var settings = {{ json_encode($settings) }};

		$('#submitForm').AjaxSubmit(
			{
				path: '/{{ Request::path() }}',
				successMessage: 'Entry successfully updated.'
			},
			function(data) {
			// Set the resource variable
			var resource = data.resource;

			// Make sure all resources have an id
			if (resource.id == null) {
				resource.id = resource.uniqueId;
			}

			// We are creating a new row
			if ($('#id').val() == '') {
				// Remove any existing placeholder
				$('#placeholder').remove();

				// Set yp the new row
				var newRow = setUpDataRow(resource, false);

				// Add the new row
				$('#dataTable tbody').append(newRow);

				// Reorder all the table rows
				entrySort();
			} else {
				// Get the existing row to edit
				var row = $('input#'+ resource.id).closest('tr');

				// Set up the new columns
				var newTds = setUpDataRow(resource, true);

				// Add the columns to the row
				row.empty().append(newTds);
			}
			$('#submitForm')[0].reset();
		});

		function setUpDataRow(resource, tdFlag) {
			// Set up the data flags for the hidden input
			var dataTags = '';
			$.each(settings.formFields, function(key, details) {
				dataTags += 'data-'+ key.toLowerCase() +'="'+ resource[key] +'" ';
			});

			// Add the hidden input
			var inputColumn = '<td style="display: none;"><input type="hidden" id="'+ resource.id +'" '+ dataTags +' /></td>';

			// Add the data columns
			var dataColumns = '';
			$.each(settings.displayFields, function(key, details) {

				// Handle links
				if (typeof details.linkLocation != 'undefined') {

					if (details.linkLocation == 'mailto') {

						dataColumns += '<td><a href="mailto:'+ resource.email +'">'+ resource.email +'</a></td>';

					} else {

						var link = details.linkLocation;
							link += (typeof details.linkProperty != 'undefined' ? resource[details.linkProperty] : '');
						dataColumns += '<td><a href="'+ link +'">'+ ucwords(resource[key]) +'</a></td>';

					}

				} else {
					dataColumns += '<td>'+ ucwords(resource[key]) +'</td>';
				}
			});

			// Add the edit link
			var editLink = '<a href="javascript:void();" class="btn btn-mini btn-primary" onClick="editDetails(\''+ resource.id +'\');">Edit</a>';

			// Add the delete link
			if (settings.deleteFlag == true) {
				var deleteLink = '<a href="{{ Request::root() }}'+ settings.deleteLink + resource[settings.deleteProperty] +'" class="confirm-remove btn btn-mini btn-danger">Delete</a>';
			} else {
				var deleteLink = '';
			}

			// Put all the new columns in order
			newRowTds = inputColumn +
				dataColumns +
				'<td class="text-center">' +
					'<div class="btn-group">' +
						editLink +
						deleteLink +
					'</div>' +
				'</td>';

			// Return the TDs if thats all we want
			if (tdFlag == true) {
				return newRowTds;
			}

			// Create the whole row
			var newRow =
				'<tr data-sort="'+ resource[settings.sortProperty] +'">' +
					newRowTds +
				'</tr>';

			return newRow;
		}

		function editDetails(objectId) {
			// Reset the form
			$('#submitForm')[0].reset();
			$('#submitForm .error').removeClass('error');
			$('#submitForm #message').empty();

			var object = $('#'+ objectId);
			$('#id').val(objectId);

			if (settings.multi == true) {
				$.each(settings.multiData, function(key, details) {
					var data = object.attr('data-'+ key);
					if (data.indexOf('[') != -1) {
						var multiSelectArray = $.parseJSON(object.attr('data-'+ key));
						$('#input_'+ key).val(multiSelectArray);
						// $('#'+ key).multiselect('refresh');
					} else {
						$('#input_'+ key).val(object.attr('data-'+ key));
					}
				});
			} else {
				$.each(settings.formFields, function(key, details) {
					$('#input_'+ key).val(object.attr('data-'+ key));
				});
			}

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