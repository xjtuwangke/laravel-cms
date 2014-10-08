<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/8
 * Time: 04:32
 */

namespace Xjtuwangke\LaravelCms;

use Illuminate\Support\Facades\URL;

class KUrl {

    protected static $version = null;

    public static function asset( $uri ){
        if( preg_match( '/^http|https\:\/\/.*/i' , $uri ) ){
            return $uri;
        }
        else{
            $version = static::$version ? static::$version . '/' : '';
            return URL::asset( "packages/xjtuwangke/laravel-cms/{$version}{$uri}" );
        }
    }
}