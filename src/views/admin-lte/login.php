<!DOCTYPE html>
<html class="bg-black">
<head>
    <meta charset="UTF-8">
    <title>登陆_<?=Config::get( 'laravel-cms::site.name' )?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset('css/basic.min.css'))?>
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset('css/admin_lte.min.css'))?>
    <?=HTML::style( Xjtuwangke\LaravelCms\KUrl::asset('css/admin.min.css'))?>
    <!--[if lte IE 8]>
    <?=HTML::script( Xjtuwangke\LaravelCms\KUrl::asset('js/respond.min.js') )?>
    <![endif]-->
</head>
<body class="bg-black">

<div class="form-box" id="login-box">
    <div class="header">管理员登陆</div>
    <?=Form::open()?>
        <div class="body bg-gray">
            <div class="form-group">
                <input type="text" name="login" class="form-control" placeholder="工号"/>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="密码"/>
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember_me"/>记住我
            </div>
        </div>
        <div class="footer">
            <button type="submit" class="btn bg-olive btn-block">登陆</button>
            <!--
            <p><a href="#">I forgot my password</a></p>
            <a href="register.html" class="text-center">Register a new membership</a>
            -->
        </div>
    </form>
    <!--
    <div class="margin text-center">
        <span>Sign in using social networks</span>
        <br/>
        <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>
        <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>
        <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>
    </div>
    -->
</div>
</body>
</html>