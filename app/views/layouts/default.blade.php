<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?=$pageTitle?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	{{ HTML::style('css/menu.css') }}
	<?=HTML::style('css/jasny-bootstrap.css')?>
	<?=HTML::style('css/bootstrap.css')?>
	<?=HTML::style('css/darkstrap.css')?>
	<?=HTML::style('css/font-awesome.min.css')?>
	<?=HTML::style('vendors/colorPicker/css/bootstrap-colorpicker.css')?>
	<?=HTML::style('vendors/AnimateCss/animate.css')?>
	<?=HTML::style('css/main.css')?>
	<script type="text/javascript" src="http://code.jquery.com/jquery.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
	<link rel="shortcut icon" href="<?php echo URL::to('/img/favicon.ico'); ?>" />
</head>
<body class="app">
	<div id="container">
		<div id="header">
		</div>
		<div id="content">
			<?php if (is_array($errors)): ?>
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Errors encountered!</strong><br />
					<?php foreach ($errors as $error): ?>
						<?=$error?><br />
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if (Session::has('login_errors')): ?>
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Errors encountered!</strong> Username or password incorrect.
				</div>
			<?php endif; ?>
			<?php if (Session::get('message')): ?>
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?=Session::get('message')?>
				</div>
			<?php endif; ?>
			<?php if(count($menu) > 0):?>
				<div id="mainMenu">
					<ul id="utopian-navigation" class="black utopian">
						<?php foreach ($menu as $item): ?>
							<?php
								$class = null;
								$style = null;
								if (count($item['subLinks']) > 0) {
									$class .= 'dropdown ';
								}
								if (Request::segment(1) == $item['link']) {
									$class .= 'active';
								}
								if ($item['text'] == 'Login' || $item['text'] == 'Logout' || $item['text'] == 'Register') {
									$style = ' style="float: right;"';
								}
								if ($class != null) {
									$class = ' class="'. $class .'"';
								}
							?>
							<li<?=$class?><?=$style?>><?=HTML::link($item['link'], $item['text'])?>
								<?php if (count($item['subLinks']) > 0): ?>
									<ul>
										@foreach ($item['subLinks'] as $subText => $subLink)
											@if (is_array($subLink))
												<li><a href="javascript: void(0);"><?=$subText?></a>
													<ul>
														@foreach ($subLink as $text => $link)
															<li><?=HTML::link($link, $text)?></li>
														@endforeach
													</ul>
												</li>
											@else
												<li><?=HTML::link($subLink, $subText)?></li>
											@endif
										@endforeach
									</ul>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<br style="clear: both;" />
				<hr />
			<?php endif; ?>
			<?php if(count($subMenu) > 0):?>
				<div id="subMenu">
					<ul id="utopian-navigation" class="black utopian subMenu">
						<?php foreach ($subMenu as $item): ?>
							<?php
								$class = null;
								if (count($item['subLinks']) > 0) {
									$class .= 'dropdown ';
								}
								if (Request::path() == $item['link']) {
									$class .= 'active';
								}
								if ($class != null) {
									$class = ' class="'. $class .'"';
								}
							?>
							<li<?=$class?>><?=HTML::link($item['link'], $item['text'])?>
								<?php if (count($item['subLinks']) > 0): ?>
									<ul>
										<?php foreach ($item['subLinks'] as $subText => $subLink): ?>
											<li><?=HTML::link($subLink, $subText)?>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<br style="clear: both;" />
			<?php endif; ?>
			<?=$content?>
		</div>
	</div>
	<div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Modal header</h3>
		</div>
		<div class="modal-body">
		</div>
		<div class="modal-footer">
		</div>
	</div>
	@yield('includeJs')
	{{ HTML::script('js/bootstrap.js') }}
	{{ HTML::script('js/bootbox.min.js') }}
	{{ HTML::script('js/jasny-bootstrap.min.js') }}
	{{ HTML::script('vendors/colorPicker/js/bootstrap-colorpicker.js') }}

    <!-- Template javascript -->
	<script type="text/javascript">
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
	{{ HTML::script('js/prefix_css.js') }}
</body>
</html>