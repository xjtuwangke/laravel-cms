<!doctype html>
<html class="no-js" lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{{ $title or '未命名页面' }}}_{{{ $site_name or '未命名站点' }}}</title>
    <meta name="description" content="{{{ $description or '' }}}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <link rel="stylesheet" href="<?=KUrl::asset('dist/css/basic.min.css')?>">
    <link rel="stylesheet" href="<?=KUrl::asset('dist/css/admin.min.css')?>">
    @if ( isset( $css ) && is_array( $css ) )
    @foreach( $css as $one )
    <link rel="stylesheet" href="{{{ KUrl::asset( $one ) }}}">
    @endforeach
    @endif

    <script src="<?=KUrl::asset('dist/js/basic.min.js')?>"></script>
    <!--[if lte IE 8]>
    <script src="<?=KUrl::asset('dist/js/respond.min.js')?>"></script>
    <![endif]-->
    <script src="<?=KUrl::asset('dist/js/admin.min.js')?>"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
{{ $navbar or '' }}
<div class="container">
    <?php
    echo KMessager::show();
    ?>
    {{ $content or '' }}
</div>
@if ( isset( $js ) && is_array( $js ) )
@foreach( $js as $one )
<script src="<?=KUrl::asset( $one )?>"></script>
@endforeach
@endif
{{ $analytics or '' }}
</body>
</html>