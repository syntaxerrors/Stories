@if (count($menu) > 0 || count($subMenu) > 0)
  <div style="margin-bottom: 60px;">
@endif
@if(count($menu) > 0)
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<div class="brand">
					<i class="icon-wrench"></i>
					Dev Toolbox
				</div>
			<div>
			<ul class="nav">
				@foreach ($menu as $key => $item)
					<?php
						$class   = array();
						$liClass = null;
						$style   = null;
						if (count($item['subLinks']) > 0) {
							$class[] = 'dropdown ';
						}
						if (Request::is($item['link']) || Request::segment(1) == $item['link']) {
							$class[] = 'active';
						}
						if (count($class) > 0) {
							$liClass = ' class="'. implode(' ', $class) .'"';
						}
					?>
					@if ($key == key(array_slice( $menu, -1, 1, true)) || $item['text'] == 'Login' || $item['text'] == 'Logout' || $item['text'] == 'Register')
						</ul>
						<ul class="nav pull-right">
					@endif
					@if (count($item['subLinks']) == 0)
						<li{{ $liClass }}>{{ HTML::linkImage($item['link'], $item['text']) }}</li>
					@else
						<li{{ $liClass }}>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"></i> {{ $item['text'] }} <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li>{{ HTML::linkImage($item['link'], $item['text']) }}</li>
								@foreach ($item['subLinks'] as $subText => $subLink)
									@if (is_array($subLink))
										@foreach ($subLink as $text => $link)
											<li>{{ HTML::linkImage($link, $text) }}</li>
										@endforeach
									@else
										 <li>{{ HTML::linkImage($subLink, $subText) }}</li>
									@endif
								@endforeach
							</ul>
						</li>
					@endif
				@endforeach
			</ul>
		</div>
	</div>
@endif
@if(count($subMenu) > 0)
	<div class="sub-navbar sub-navbar-fixed-top">
		<div class="sub-navbar-inner">
			<div class="container-fluid">
				<ul class="sub-nav">
					@foreach ($subMenu as $key => $item)
						<?php
							$class   = array();
							$liClass = null;
							$style   = null;
							if (count($item['subLinks']) > 0) {
								$class[] = 'dropdown ';
							}
							if (Request::is($item['link']) || Request::segment(1) == $item['link']) {
								$class[] = 'active';
							}
							if (count($class) > 0) {
								$liClass = ' class="'. implode(' ', $class) .'"';
							}
						?>
						@if (count($item['subLinks']) == 0)
							<li{{ $liClass }}>{{ HTML::link($item['link'], $item['text']) }}</li>
						@else
							<li{{ $liClass }}>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"></i> {{ $item['text'] }} <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>{{ HTML::link($item['link'], $item['text']) }}</li>
									@foreach ($item['subLinks'] as $subText => $subLink)
										@if (is_array($subLink))
											@foreach ($subLink as $text => $link)
												<li>{{ HTML::linkImage($link, $text) }}</li>
											@endforeach
										@else
											<li>{{ HTML::linkImage($subLink, $subText) }}</li>
										@endif
									@endforeach
								</ul>
							</li>
						@endif
					@endforeach
				</ul>
			</div>
		</div>
	</div>
@endif
@if (count($menu) > 0 || count($subMenu) > 0)
	</div>
@endif
<br style="clear: both;" />