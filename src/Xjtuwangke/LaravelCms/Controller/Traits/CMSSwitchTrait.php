<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-17
 * Time: 23:53
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;

trait CMSSwitchTrait {

    public static function _routes_switch(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::post( "{$_uri}/switch/on" , [ 'as' => "{$_as}.edit.switch.on" , 'uses' => "{$_class}@switchON" ] );
        Route::post( "{$_uri}/switch/off" , [ 'as' => "{$_as}.edit.switch.off" , 'uses' => "{$_class}@switchOFF" ] );
    }

    /**
     * 将资源切换到启用状态
     */
    public function switchON(){
        $id = Input::get( 'id' );
        $items = static::queryAll()->withTrashed()->find( $id );
        $count = 0;
        foreach( $items as $item ){
            $item->switchOn();
            $count++;
        }
        return json_encode( [ 'result' => 'success' , 'count' => $count , 'action' => 'on']);
    }

    /**
     * 将资源切换到禁用状态
     */
    public function switchOFF(){
        $id = Input::get( 'id' );
        $items = static::queryAll()->withTrashed()->find( $id );
        $count = 0;
        foreach( $items as $item ){
            $item->switchOff();
            $count++;
        }
        return json_encode( [ 'result' => 'success' , 'count' => $count , 'action' => 'off']);
    }

} 