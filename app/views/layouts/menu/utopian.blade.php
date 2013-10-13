@if(count($menu) > 0)
	<div id="mainMenu">
		<ul id="utopian-navigation" class="black utopian">
			@foreach ($menu as $item)
				<?php
					$class = null;
					$style = null;
					if (count($item['subLinks']) > 0) {
						$class .= 'dropdown ';
					}
					if (Request::is($item['link']) || Request::segment(1) == $item['link']) {
						$class .= 'active';
					}
					if ($item['text'] == 'Login' || $item['text'] == 'Logout' || $item['text'] == 'Register') {
						$style = ' style="float: right;"';
					}
					if ($class != null) {
						$class = ' class="'. $class .'"';
					}
				?>
				<li{{ $class }}{{ $style }}>{{ HTML::linkImage($item['link'], $item['text']) }}
					@if (count($item['subLinks']) > 0)
						<ul>
							@foreach ($item['subLinks'] as $subText => $subLink)
								@if (is_array($subLink))
									<li><a href="javascript: void(0);">{{ $subText }}</a>
										<ul>
											@foreach ($subLink as $text => $link)
												<li>{{ HTML::linkImage($link, $text) }}</li>
											@endforeach
										</ul>
									</li>
								@else
									<li>{{ HTML::linkImage($subLink, $subText) }}</li>
								@endif
							@endforeach
						</ul>
					@endif
				</li>
			@endforeach
		</ul>
	</div>
	<br style="clear: both;" />
	<hr />
@endif
@if(count($subMenu) > 0)
	<div id="subMenu">
		<ul id="utopian-navigation" class="black utopian subMenu">
			@foreach ($subMenu as $item)
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
				<li{{ $class }}>{{ HTML::linkImage($item['link'], $item['text']) }}
					@if (count($item['subLinks']) > 0)
						<ul>
							@foreach ($item['subLinks'] as $subText => $subLink)
								<li>{{ HTML::linkImage($subLink, $subText) }}
							@endforeach
						</ul>
					@endif
				</li>
			@endforeach
		</ul>
	</div>
	<br style="clear: both;" />
@endif