@include('base.header')

    @yield('css')
    </head>

    <body>
        <div id="wrapper">
            @include('base.side')
            <!-- /. NAV SIDE  -->
            @yield('main')
        </div>
        <!-- /. WRAPPER  -->
    </body>
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <!-- Bootstrap Js -->
    <script src="/assets/js/bootstrap.min.js"></script>
    <!-- Layer Js -->
    <script src="/assets/plugins/layer/layer.js"></script>
    <!-- Metis Menu Js -->
    <script src="/assets/js/jquery.metisMenu.js"></script>

    <!-- Morris Chart Js -->
    <script src="/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="/assets/js/morris/morris.js"></script>

    <!-- Custom Js -->
    <script src="/assets/js/custom-scripts.js"></script>

    @yield('js')

</html>