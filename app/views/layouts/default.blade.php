<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?=$pageTitle?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?php echo URL::to('/img/favicon.ico'); ?>" />

	<!-- Bootstrap styles -->
	{{ HTML::style('css/jasny-bootstrap.css') }}
	{{ HTML::style('css/bootstrap.css') }}
	{{ HTML::style('css/darkstrap.css') }}

	<!-- Extra styles -->
	{{ HTML::style('css/font-awesome.min.css') }}
	{{ HTML::style('vendors/colorPicker/css/bootstrap-colorpicker.css') }}
	{{ HTML::style('vendors/AnimateCss/animate.css') }}
	{{ HTML::style('/vendor/select2/select2.css') }}
	{{ HTML::style('/vendor/messenger/build/css/messenger.css') }}
	{{ HTML::style('/vendor/messenger/build/css/messenger-theme-future.css') }}
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />

	@yield('css')

	<!-- Local styles -->
	{{ HTML::style('css/menu.css') }}
	{{ HTML::style('css/master.css') }}
</head>
<body class="app">
	<div id="container">
		<div id="header">
		</div>
		<div id="content">
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

    <!-- javascript-->
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap-transition.js"></script>
    <script src="/js/bootstrap-alert.js"></script>
    <script src="/js/bootstrap-modal.js"></script>
    <script src="/js/bootstrap-dropdown.js"></script>
    <script src="/js/bootstrap-scrollspy.js"></script>
    <script src="/js/bootstrap-tab.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/bootstrap-popover.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/bootstrap-collapse.js"></script>
    <script src="/js/bootstrap-carousel.js"></script>
    <script src="/js/bootstrap-typeahead.js"></script>
    <script src="/js/prefixer.js"></script>
    <script src="/vendor/select2/select2.js"></script>
    <script src="/vendor/bootbox/bootbox.min.js"></script>
    <script src="/vendor/messenger/build/js/messenger.min.js"></script>
    <script src="/vendor/messenger/build/js/messenger-theme-future.js"></script>
    <script src="/js/AHScoreboard.js"></script>
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

        var mainErrors = <?=(Session::get('errors') != null ? json_encode(implode('<br />', Session::get('errors'))) : 0)?>;
        var mainStatus = <?=(Session::get('message') != null ? json_encode(Session::get('message')) : 0)?>;
        var mainLogins = <?=(Session::get('login_errors') != null ? json_encode(Session::get('login_errors')) : 0)?>;

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