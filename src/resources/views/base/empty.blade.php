<!DOCTYPE html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta name="renderer" content="webkit">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('css')

    <link rel="stylesheet" href="{{ asset('vendor/qla/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/qla/css/font-awesome.min.css')}}"/>

    <link rel="stylesheet" href="{{ asset('vendor/qla/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('vendor/qla/css/ace-part2.min.css') }}" class="ace-main-stylesheet"/>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('vendor/qla/css/ace-skins.min.css') }}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('vendor/qla/css/ace-ie.min.css') }}"/>
    <![endif]-->

    <script src="{{ asset('vendor/qla/js/ace/ace-extra.min.js') }}"></script>

    <!--[if lt IE 8]>
    <script src="{{ asset('vendor/qla/js/respond-1.4.2.min.js') }}"></script>
    <script src="{{ asset('vendor/qla/js/html5shiv-3.7.3.min.js') }}"></script>
    <script src="{{ asset('vendor/qla/js/placeholders-4.0.1.min.js') }}"></script>
    <![endif]-->


    <style type="text/css">
        .gritter-title {
            font-size: 1.5em;
        }

        .gritter-item p {
            font-size: 1.3em;
        }

        .fixed-table-toolbar {
            padding-left: 5px;
            margin-top: -15px;
        }

        .bs-bars {
            margin-top: 0px !important;
        }

        .pull-left {
            margin-top: 0px !important;
        }

        .pull-right.search {
            margin-top: 15px !important;
        }

        form label {
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="main-content">
        @yield('content_header')
        <div class="page-content">
            @yield('content')
        </div>
    </div>
</div>

<!--[if IE]>
<script>
    window.jQuery ||  document.write('<script src="{{ asset('vendor/qla/js/jquery/jquery-1.12.4.min.js') }}"><\/script>')
</script>
<![endif]-->

<script>
    window.jQuery || document.write('<script src="{{ asset('vendor/qla/js/jquery/jquery-2.1.4.min.js') }}"><\/script>')
    if('ontouchstart' in document.documentElement)
        document.write('<script src="{{ asset('vendor/qla/js/jquery/jquery.mobile.custom.min.js') }}" ><\/script>)');
</script>

<!-- lhgdialog scripts -->
<script>$.dialog || document.write('<script src="{{ asset('vendor/qla/js/lhgdialog/lhgdialog.js?skin=discuz') }}"><\/script>')</script>

<script src="{{ asset('vendor/qla/js/js.cookie-2.1.4.min.js') }}"></script>
@stack('pre_js')
<script src="{{ asset('vendor/qla/js/bootstrap/bootstrap.min.js') }}"></script>

<script src="{{ asset('vendor/qla/js/ace/ace-elements.min.js') }}"></script>
<script src="{{ asset('vendor/qla/js/ace/ace.min.js') }}"></script>

<script type="text/javascript">
    function resizeFrame() {
        if (top !== window) {
            var content_iframe = window.parent.document.getElementById("iframe");//获取iframeID

            var s = $(top).height()-130;
            $(content_iframe).height(s);

            var bodyH = $(content_iframe).contents().find("body").get(0).scrollHeight,
                htmlH = $(content_iframe).contents().find("html").get(0).scrollHeight,
                maxH = Math.max(bodyH, htmlH), minH = Math.min(bodyH, htmlH),
                h = $(content_iframe).height() >= maxH ? maxH : minH;

            if (h < s) h = s;
            $(content_iframe).height(h);
            $(content_iframe).contents().find("body").height(h);
        }


    }

    function hideBreadcrumb() {
        $('.breadcrumbs').hide();
    }
</script>

@stack('js')
@stack('post_js')
</body>
</html>