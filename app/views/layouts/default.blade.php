<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dev Toolbox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap styles -->
    {{ HTML::style('/css/bootstrap.min.css') }}
    {{ HTML::style('/css/bootstrap-responsive.min.css') }}
    {{ HTML::style('/css/darkStrap.css') }}

    <!-- Extra styles -->
    {{ HTML::style('/vendor/code-prettify/styles/tomorrow-night.css') }}
    {{ HTML::style('/vendor/font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('/vendor/select2/select2.css') }}

    @yield('css')

    <!-- Local styles -->
    {{ HTML::style('/css/master.css') }}

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="/favicon.ico">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <div class="brand">
            <i class="icon-trophy"></i>
            AH Scoreboard
          </div>
          <div class="">
            <ul class="nav">
                <li {{ routeIs( 'dashboard') }}><a href="/dashboard"><i class="icon-home"></i> Scoreboard</a></li>
                  <li {{ routeIs( 'about') }}><a href="/about"><i class="icon-question-sign"></i> About</a></li>
                @if (!isset($activeUser))
                  <li {{ routeIs( 'registration') }}><a href="/registration"><i class="icon-group"></i> Register</a></li>
                @endif
            </ul>
            @if (isset($activeUser))
                    <ul class="nav pull-right">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> {{ $activeUser->username }} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="/user/settings"><i class="icon-cogs"></i> Settings</a></li>
                          <li class="divider"></li>
                          @if ($activeUser->id == 1 || $activeUser->id == 2)
                            <li {{ routeIs( 'admin') }}><a href="/admin/dashboard"><i class="icon-beer"></i> Admin Panel</a></li>
                            <li class="divider"></li>
                          @endif
                          <li><a href="/logout"><i class="icon-off"></i> Logout</a></li>
                        </ul>
                      </li>
                    </ul>
            @else
                <form class="navbar-form pull-right" method="POST" action="/login">
                  <input class="span2" type="text" placeholder="Username" name="username">
                  <input class="span2" type="password" placeholder="Password" name="password">
                  <button type="submit" class="btn btn-inverse">Sign in</button>
                </form>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">

        @if (isset($content))
            {{ $content }}
        @endif

    </div> <!-- /container -->

    <!-- Modal -->
    <div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
    <script src="/vendor/code-prettify/src/prettify.js"></script>
    <script src="/js/prefixer.js"></script>
    <script src="/vendor/select2/select2.js"></script>
    <script>
      // Work around for multi data toggle modal
      // http://stackoverflow.com/questions/12286332/twitter-bootstrap-remote-modal-shows-same-content-everytime
      $('body').on('hidden', '#modal', function () {
        $(this).removeData('modal');
      });
    </script>
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-9093798-3']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>

    @yield('js')

  </body>
</html>