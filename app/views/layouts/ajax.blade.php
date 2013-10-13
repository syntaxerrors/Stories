@yield('ajaxCss')

@if (isset($content))
	{{ $content }}
@endif

@yield('jsInclude')
<script>
	$(document).ready(function() {
		$("a[rel=popover]").popover();
		$("a.confirm-remove").click(function(e) {
			e.preventDefault();
			var location = $(this).attr('href');
			bootbox.dialog({
				message: "Are you sure you want to remove this item?",
				buttons: {
					success: {
						label: "Yes",
						className: "btn-primary",
						callback: function() {
							window.location.replace(location);
						}
					},
					danger: {
						label: "No",
						className: "btn-primary"
					}
				}
			});
		});
		$("a.confirm-continue").click(function(e) {
			e.preventDefault();
			var location = $(this).attr('href');
			bootbox.dialog({
				message: "Are you sure you want to continue?",
				buttons: {
					danger: {
						label: "No",
						className: "btn-primary"
					},
					success: {
						label: "Yes",
						className: "btn-primary",
						callback: function() {
							window.location.replace(location);
						}
					},
				}
			});
		});
		// Work around for multi data toggle modal
		// http://stackoverflow.com/questions/12286332/twitter-bootstrap-remote-modal-shows-same-content-everytime
		$('body').on('hidden', '#modal', function () {
			$(this).removeData('modal');
		});
		@yield('onReadyJs')
	});
</script>
@yield('js')