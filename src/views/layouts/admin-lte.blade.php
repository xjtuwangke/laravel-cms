<!doctype html>
<html class="no-js" lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{{ $title or '' }}}_管理页面_{{{ $site_name or '未命名站点' }}}</title>
    <meta name="description" content="{{{ $description or '' }}}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset('css/basic.min.css'))?>
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset('css/admin_lte.min.css'))?>
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset('css/admin.min.css'))?>
    @if ( isset( $css ) && is_array( $css ) )
    @foreach( $css as $one )
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset($one))?>
    @endforeach
    @endif
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('js/basic.min.js') )?>
    <!--[if lte IE 8]>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('js/respond.min.js') )?>
    <![endif]-->
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('js/admin.min.js') )?>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('js/admin_lte.min.js') )?>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('ckeditor/ckeditor.js') )?>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('ckeditor/config.js') )?>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('ckeditor/styles.js') )?>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('ckeditor/lang/zh-cn.js') )?>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('js/admin_custom.min.js') )?>
</head>
<body class="skin-blue">
<!-- header logo: style can be found in header.less -->
<header class="header">
<a href="{{ URL::action( 'admin.index' ) }}" class="logo">
    <!-- Add the class icon to your logo image or logo icon to add the margining -->
    <div id="gofarms-logo"></div>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
<!-- Sidebar toggle button-->
<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</a>
<div class="navbar-left">
    @if( isset( $shortcuts ) && is_array( $shortcuts ) )
        @foreach( $shortcuts as $one )
        <ul class="nav navbar-nav">
            <li class="dropdown lock-screen-menu">
                <a href="{{{ $one->url or 'javascript:;' }}}" target="_blank">
                    <i class="fa fa-spin"></i>{{{ $one->title or '' }}}
                </a>
            </li>
        </ul>
        @endforeach
    @endif
</div>
<div class="navbar-right">
    <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        <!-- Notifications: style can be found in dropdown.less -->
        <li class="dropdown report-bug-menu">
            <a href="mailto:{{{ Config::get('aravel-cms::site.email') }}}">
                <i class="fa fa-bug"></i>上报Bug(版本:{{{ Config::get('laravel-cms::site.version') }}})
            </a>
        </li>
        <li class="dropdown lock-screen-menu">
            <a href="<?=URL::action('admin.lock')?>">
                <i class="fa fa-lock"></i>锁屏
            </a>
        </li>
    <!-- User Account: style can be found in dropdown.less -->
    {{ $usermenu or '' }}
    </ul>
</div>
</nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!--
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/avatar3.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Hello, Jane</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {{ $navbar or '' }}
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $title or '' }}
        <small>{{ $small_title or '' }}</small>
    </h1>
    <!--
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
    -->
</section>
<!-- Main content -->
<section class="content">
    {{ \Xjtuwangke\LaravelCms\Elements\KMessager::show() }}
    {{ $content or '' }}
</section><!-- /.content -->
</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
@if ( isset( $js ) && is_array( $js ) )
@foreach( $js as $one )
<?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset( $one ) )?>
@endforeach
@endif
{{ $analytics or '' }}
</body>
</html>
