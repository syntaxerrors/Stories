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
			bootbox.confirm("Are you sure you want to remove this item?", "No", "Yes", function(confirmed) {
				if(confirmed) {
					window.location.replace(location);
				}
			});
		});
		$("a.confirm-continue").click(function(e) {
			e.preventDefault();
			var location = $(this).attr('href');
			bootbox.confirm("Are you sure you want to continue?", "No", "Yes", function(confirmed) {
				if(confirmed) {
					window.location.replace(location);
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