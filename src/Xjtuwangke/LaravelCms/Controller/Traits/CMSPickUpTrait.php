<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-28
 * Time: 12:18
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Xjtuwangke\Random\KRandom;

/**
 * Class CMSPickUpTrait
 * 可以在modal选择框中选择
 */
trait CMSPickUpTrait {

    public static function _routes_pickup(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::post( "{$_uri}/show_picked_up_item" , [ 'as' => "{$_as}.show.picked_up_item" , 'uses' => "{$_class}@show_picked_up_item" ] );
        Route::post( "{$_uri}/pick_up_item_search" , [ 'as' => "{$_as}.show.search_pick_up_item" , 'uses' => "{$_class}@pick_up_item_search" ] );
    }

    public function show_picked_up_item(){
        $id = Input::get( 'id' );
        $field = Input::get( 'field' );
        return static::display_picked_up_item( $id , $field );
    }

    public function pick_up_item_search(){
        $pattern = Input::get( 'pattern' );
        $page    = Input::get( 'page' , 1 );
        $field   = Input::get( 'field' );
        $class   = static::$class;
        $action = static::$action.'.show.picked_up_item';
        return View::make( 'laravel-cms::cms/picked-up-items/' . static::$class . '_search' )
            ->with( 'pattern' , $pattern )
            ->with( 'page' , $page )
            ->with( 'class' , $class )
            ->with( 'field' , $field )
            ->with( 'action' , $action );
    }

    public static function display_search_modal( $id , $field ){
        $class = static::$class;
        if( ! $id ){
            $id = KRandom::getRandStr( 32 , 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );
        }
        $search_action = static::$action.'.show.search_pick_up_item';
        return View::make( 'laravel-cms::cms/picked-up-items/modal-search' )->with( 'id' , $id )->with( 'field' , $field )->with( 'search_action' , $search_action )->render();
    }

    public static function display_picked_up_item( $id , $field ){
        $item = static::queryAll()->find( $id );
        $action = static::$action . ".show.picked_up_item";
        return View::make( 'laravel-cms::cms/picked-up-items/' . static::$class . '_show' )->with( 'item' , $item )->with( 'field' , $field )->with('action' , $action )->render();
    }

} 