<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?=$pageTitle?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?php echo URL::to('/img/favicon.ico'); ?>" />

	<!-- Bootstrap styles -->
	{{ HTML::style('/css/jasny-bootstrap.css') }}
	{{ HTML::style('/css/bootstrap.css') }}
	{{ HTML::style('/css/darkstrap.css') }}

	<!-- Extra styles -->
	{{ HTML::style('/vendor/font-awesome/css/font-awesome.min.css') }}
	{{ HTML::style('/vendors/colorPicker/css/bootstrap-colorpicker.css') }}
	{{ HTML::style('/vendor/messenger/build/css/messenger.css') }}
	{{ HTML::style('/vendor/messenger/build/css/messenger-theme-future.css') }}
	{{ HTML::style('http://fonts.googleapis.com/css?family=Orbitron') }}
	<!-- {{ HTML::style('vendors/AnimateCss/animate.css') }} -->
	<!-- {{ HTML::style('/vendor/select2/select2.css') }} -->

	@yield('css')

	<!-- Local styles -->
	{{ HTML::style('/css/menu.css') }}
	@if (!Auth::guest() && File::exists($activeUser->theme))
		{{ HTML::style($activeUser->themeStyle) }}
	@else
		{{ HTML::style('/css/master.css') }}
	@endif
	{{ HTML::style('http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css') }}
</head>
<body class="app">
	<div id="container">
		<div id="header"></div>
		<div id="content">
			@include('layouts.menu.'. Config::get('app.menu'))
			{{ $content }}
		</div>
	</div>
	<div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Modal header</h3>
		</div>
		<div class="modal-body"></div>
		<div class="modal-footer"></div>
	</div>

	<!-- javascript-->
	{{ HTML::script('/js/jquery-1.10.2.min.js') }}
	{{ HTML::script('/js/bootstrap.min.js') }}
	{{ HTML::script('/js/jasny-bootstrap.js') }}
	{{ HTML::script('/js/prefixer.js') }}
	{{ HTML::script('/vendor/bootbox/bootbox.js') }}
	{{ HTML::script('/vendor/messenger/build/js/messenger.min.js') }}
	{{ HTML::script('/vendor/messenger/build/js/messenger-theme-future.js') }}
	{{ HTML::script('/js/master.js') }}

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

		Messenger.options = {
			extraClasses: 'messenger-fixed messenger-on-top',
			theme: 'future'
		}

		var mainErrors = {{ (Session::get('errors') != null ? json_encode(implode('<br />', Session::get('errors'))) : 0) }};
		var mainStatus = {{ (Session::get('message') != null ? json_encode(Session::get('message')) : 0) }};
		var mainLogins = {{ (Session::get('login_errors') != null ? json_encode(Session::get('login_errors')) : 0) }};

		if (mainLogins == true) {
			Messenger().post({message: 'Username or password incorrect.',type: 'error'});
		}
		if (mainErrors != 0) {
			Messenger().post({message: mainErrors,type: 'error'});
		}
		if (mainStatus != 0) {
			Messenger().post({message: mainStatus});
		}
		@yield('onReadyJs')
	});
	</script>

	@yield('js')

</body>
</html>