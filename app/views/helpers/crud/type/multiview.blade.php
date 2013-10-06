<div class="row-fluid">
	<div class="span12" id="listPanel">
		<div class="well well-small">
			<div class="well-title">
				{{ $settings->title }}
			</div>
			<table class="table table-hover table-striped table-condensed" id="dataTable">
				<thead>
					<tr>
						<th style="display: none;"></th>
						<?php
							$width = (90 / count($settings->multiViewColumns)) .'%';
						?>
						@foreach ($settings->multiViewColumns as $column)
							<th class="text-left" style="width: {{ $width }}">{{ ucwords(str_replace('_', ' ', $column)) }}</th>
						@endforeach
						<th class="text-center" style="width: 10%;">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($settings->multiViewCollection as $collection)
						<tr>
							<td style="display: none;">
								<input type="hidden"	id="{{ $collection->id }}" data-multi="{{{ json_encode($collection->{$settings->multiViewProperty}->id->toArray()) }}}" />
							</td>
							<td>{{ $collection->{$settings->multiViewDetails['name']} }}</td>
							<td>
								@foreach ($collection->{$settings->multiViewProperty} as $property)
									{{ $property->{$settings->multiViewPropertyDetails['name']} }}<br />
								@endforeach
							</td>
							<td class="text-center">
								<div class="btn-group">
									@if (is_int($collection->id))
										<a href="javascript:void(0)" class="btn btn-mini btn-primary" onClick="editDetails({{ $collection->id }});">Edit</a>
									@else
										<a href="javascript:void(0)" class="btn btn-mini btn-primary" onClick="editDetails('{{ $collection->id }}');">Edit</a>
									@endif
									@if (!isset($settings->deleteFlag) || $settings->deleteFlag == true)
										{{ HTML::link($settings->deleteLink . $collection->{$settings->deleteProperty}, 'Delete', array('class' => 'confirm-remove btn btn-mini btn-danger')) }}
									@endif
								</div>
							</td>
						</tr>
					@endforeach
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
						type: 'multiView',
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
							var row = $('input#'+ results.resourceId).closest('tr');

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

			$('#input_'+ settings.multiViewDetails.field).val(objectId);
			var multi = $.parseJSON(object.attr('data-multi'));
			$('#input_'+ settings.multiViewPropertyDetails.field).val(multi);

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