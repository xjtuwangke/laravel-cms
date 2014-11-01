<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-7-28
 * Time: 19:59
 */

Route::get( 'admin/forbidden' , array( 'as' => 'admin.forbidden' , function(){
    $html = <<<HTML
<html>
<head>
<meta charset="utf-8">
<body>
<h1>您没有相应的权限</h1>
</body>
</head>
</html>
HTML;
    return Response::make( $html , 401);
}));

Route::filter( 'adminFilter' , 'Xjtuwangke\LaravelCms\Filters\AdminFilter' );

Route::get( 'admin/login' , [ 'as' => 'admin.login' , 'uses' => 'Xjtuwangke\LaravelCms\Controllers\AdminController@login' ] );
Route::post( 'admin/login' , [ 'before' => [ 'csrf' ] , 'uses' => 'Xjtuwangke\LaravelCms\Controllers\AdminController@login' ]);

UploadifyController::registerRoutes();

Route::group( ['before'=> ['adminFilter'] ] , function(){

    Route::get('admin/logout' , [ 'as' => 'admin.logout' , 'uses' => 'Xjtuwangke\LaravelCms\Controllers\AdminController@logout' ] );
    Route::get( 'admin' , [ 'as' => 'admin.index' , 'uses' => 'Xjtuwangke\LaravelCms\Controllers\AdminController@index' ] );

    Route::get( 'admin/lock' , [ 'as' => 'admin.lock' , 'uses' => 'Xjtuwangke\LaravelCms\Controllers\AdminController@lock' ] );

    Route::post( 'admin/unlock' , [ 'as' => 'admin.unlock' , 'uses' => 'Xjtuwangke\LaravelCms\Controllers\AdminController@unlock' ] );

    Route::post( "admin/uploadify/image" , [ 'before' => [ 'csrf' ] , 'as' => "admin.uploadify.image" , 'uses' => "UploadifyController@image" ] );

    \Xjtuwangke\LaravelCms\Controllers\AdminAdminController::registerRoutes();
    //\Xjtuwangke\LaravelCms\Controllers\AdminRoleController::registerRoutes();

    if( file_exists( app_path( 'routes/admin.routes.php') ) ){
        include( app_path( 'routes/admin.routes.php') );
    }

});