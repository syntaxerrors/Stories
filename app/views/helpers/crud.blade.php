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
							@if (isset($details['multi']))
								<th class="text-left">{{ ucwords(str_replace('_', ' ', $details['multiTitle'])) }}</th>
							@endif
						@endforeach
						<th class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					@if (count($resources) > 0)
						@foreach ($resources as $resource)
							<tr data-sort="{{ $resource->{$settings->sort} }}">
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
									@if (isset($details['link']) && $details['link'] != null)
										@if ($details['link'] == 'mailto')
											<td>{{ HTML::mailto($resource->email, HTML::email($resource->email)) }}</td>
										@else
											<td>
												{{ HTML::link($details['link'] . (isset($details['linkProperty']) ? $resource->{$details['linkProperty']} : null), ucwords($resource->{$key}))}}
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
					@if ($details['field'] == 'image')
						<div class="fileupload fileupload-new" data-provides="fileupload" data-name="image">
							<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><?=HTML::image('img/noImage.gif', null, array('style' => 'width: 200px;'))?></div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="line-height: 20px;"></div>
							<div>
								<span class="btn btn-file btn-inverse"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input id="image" type="file" /></span>
								<a href="javascript: void(0);" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">Remove</a>
							</div>
						</div>
					@endif
					<div class="control-group">
						<div class="controls">
							@if ($details['field'] == 'text')
								{{ Form::text($key, null, array('id' => $key, 'placeholder' => (isset($details['placeholder']) ? $details['placeholder'] : ucwords($key) ))) }}
							@elseif ($details['field'] == 'email')
								{{ Form::email($key, null, array('id' => $key, 'placeholder' => (isset($details['placeholder']) ? $details['placeholder'] : ucwords($key) ))) }}
							@elseif ($details['field'] == 'textarea')
								{{ Form::textarea($key, null, array('id' => $key, 'placeholder' => (isset($details['placeholder']) ? $details['placeholder'] : ucwords($key) ))) }}
							@elseif ($details['field'] == 'select')
								{{ Form::select($key, $details['selectArray'], null, array('id' => $key)) }}
							@elseif ($details['field'] == 'multiselect')
								{{ Form::select($key .'[]', $details['selectArray'], null, array('id' => $key, 'multiple' => 'multiple')) }}
							@endif
						</div>
					</div>
				@endforeach
				<div class="controls">
					{{ Form::reset('Reset Fields', array('class' => 'btn btn-small btn-primary')) }}
					{{ Form::submit('Submit', array('class' => 'btn btn-small btn-primary', 'id' => 'jsonSubmit')) }}
				</div>
			{{ Form::close(); }}
			<div id="message"></div>
		</div>
	</div>
</div>
<div id="helpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    	<h3 id="myModalLabel">Help</h3>
  	</div>
  	<div class="modal-body">
		<div class="well well-small">
			<ul>
				<li>The <span class="text-info">Existing Id</span> field determins if you are editing an existing entry or creating a new one.</li>
				<li>If it is <span class="text-info">empty</span>, you are making a <span class="text-info">new</span> one.</li>
				<li>If it is <span class="text-info">populated</span>, you are editing an <span class="text-info">existing</span> one.</li>
				<li>If you would like to change between editing and creating, use the <span class="text-info">Reset Fields</span> button.</li>
			</ul>
		</div>
  	</div>
  	<div class="modal-footer">
	    <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Close</button>
  	</div>
</div>
@section('js')
	<script type="text/javascript">
		var settings = {{ json_encode($settings) }};

		function editDetails(objectId) {
			var object = $('#'+ objectId);
			$('#id').val(objectId);

			if (settings.multi == true) {
				$.each(settings.multiData, function(key, details) {
					var data = object.attr('data-'+ key);
					if (data.indexOf('[') != -1) {
						var multiSelectArray = $.parseJSON(object.attr('data-'+ key));
						$('#'+ key).val(multiSelectArray);
						// $('#'+ key).multiselect('refresh');
					} else {
						$('#'+ key).val(object.attr('data-'+ key));
					}
				});
			} else {
				$.each(settings.formFields, function(key, details) {
					$('#'+ key).val(object.attr('data-'+ key));
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
			}
		}

		$('#jsonSubmit').on('click', function(event) {
			event.preventDefault();
			$('#jsonSubmit').attr('disabled', 'disabled');

			$('.error').removeClass('error');
			$('#message').empty().append('<i class="icon-spinner icon-spin"></i>');

			var data = $('#submitForm').serialize();

			$.post('/{{ Request::path() }}', data, function(response) {

				if (response.status == 'success') {
					$('#message').empty().append('Entry successfully updated.');
				}
				if (response.status == 'error') {
					$('#message').empty();
					$.each(response.errors, function (key, value) {
						$('#' + key).addClass('error');
						$('#message').append('<span class="text-error">'+ value +'</span><br />');
					});
				}
			});
		});

		// $('#jsonSubmit').click(function(event) {
		// 	event.preventDefault();
		// 	$('#message').empty().append('<i class="icon-spinner icon-spin"></i>');

		// 	if ($('#image').val() != null) {
		// 		var data = $('#submitForm').serialize() +'&image='+ encodeURIComponent($('#image').val());
		// 	} else {
		// 		var data = $('#submitForm').serialize();
		// 	}
		// 	$.post('/{{ Request::path() }}', data, function(data) {
		// 		var resource = $.parseJSON(data);

		// 		try {
		// 			if (resource.id != null || resource.uniqueId != null) {
		// 				$('#message').empty().append('Entry successfully updated.');

		// 				if ($('#id').val() == '') {
		// 					$('#placeholder').remove();
		// 					// Set up the columns
		// 					var newRowTds = '';
		// 					$.each(settings.displayFields, function(key, details) {
		// 						if (details.link && details.link !== null) {
		// 							if (details.link == 'mailto') {
		// 								newRowTds += '<td><a href="mailto:'+ resource.email +'">'+ resource.email +'</a></td>';
		// 							} else {
		// 								console.log(details.link);
		// 								var link = details.link;
		// 									link += (typeof details.linkProperty != 'undefined' ? resource[details.linkProperty] : '');
		// 								newRowTds += '<td><a href="'+ link +'">'+ ucwords(resource[key]) +'</a></td>';
		// 							}
		// 						} else {
		// 							newRowTds += '<td>'+ ucwords(resource[key]) +'</td>';
		// 						}
		// 					});
		// 					var dataTags = '';
		// 					$.each(settings.formFields, function(key, details) {
		// 						dataTags += 'data-'+ key.toLowerCase() +'="'+ resource[key] +'" ';
		// 					});

		// 					var newRow =
		// 						'<tr data-sort="'+ resource[settings.sort] +'">' +
		// 							'<td style="display: none;">'+
		// 								'<input type="hidden" id="'+ resource.id +'" '+ dataTags +' />' +
		// 							'</td>' +
		// 							newRowTds +
		// 							'<td class="text-center">' +
		// 								'<div class="btn-group">' +
		// 									'<a href="javascript:void();" class="btn btn-mini btn-primary" onClick="editDetails('+ resource.id +');">Edit</a>' +
		// 									'<a href="{{ Request::root() }}'+ settings.deleteLink + resource[settings.deleteProperty] +'" class="confirm-remove btn btn-mini btn-danger">Delete</a>' +
		// 								'</div>' +
		// 							'</td>' +
		// 						'</tr>';

		// 					$('#dataTable tbody').append(newRow);

		// 					entrySort();
		// 				}
		// 				$('#submitForm')[0].reset();
		// 			} else {
		// 				var message = '';
		// 				$.each(resource, function (key, error){
		// 					message += error[0] +'<br />';
		// 				});

		// 				$('#message').empty().append('<span class="text-error">'+ message +'</span');
		// 			}
		// 		} catch (e) {
		// 			$('#message').empty().append('<span class="text-error">'+ data +'</span>');
		// 		}
		// 	});
		// });

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