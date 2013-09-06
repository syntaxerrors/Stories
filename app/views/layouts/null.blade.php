@if (isset($content))
	{{ $content }}
@endif
<script>
	$(document).ready(function() {
		// Work around for multi data toggle modal
		// http://stackoverflow.com/questions/12286332/twitter-bootstrap-remote-modal-shows-same-content-everytime
		$('body').on('hidden', '#modal', function () {
			$(this).removeData('modal');
		});
	});
</script>