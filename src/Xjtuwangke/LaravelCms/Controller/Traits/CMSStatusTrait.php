<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-28
 * Time: 17:44
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Xjtuwangke\LaravelCms\Auth\Permission;

trait CMSStatusTrait {

    public static function _routes_status(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::post( "{$_uri}/status/change" , [ 'as' => "{$_as}.edit.status" , 'uses' => "{$_class}@change_status" ] );
    }

    public static function groupChangeStatus( $status , $group_btn = '修改状态'){
        $token = Session::getToken();
        $li = '';
        foreach( $status as $one ){
            $url = $one['url'];
            $text = $one['text'];
            $li.= <<<LI
<li><a class="" data-role="table-group-action" href="javascript:;" data-attr-token="{$token}" data-attr-url="{$url}">{$text}</a></li>
LI;
        }
        $html = <<<HTML
<div class="btn-group" data-role="table-selecte-none" data-attr-token="{$token}">
      <button type="button" class="btn btn-xs btn-info btn-current-status">{$group_btn}</button>
      <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" role="menu">
      {$li}
      </ul>
</div>
HTML;
        return $html;

    }

    /**
     * 切换资源status
     */
    public function change_status( $status = null ){
        $id = Input::get( 'id' );
        if( ! is_array( $id ) ){
            $id = [ $id ];
        }
        if( null == $status ){
            $status = Input::get( 'status' );
        }
        $items = static::queryAll()->withTrashed()->findMany( $id );
        $count = 0;
        foreach( $items as $item ){
            $item->changeStatus( $status );
            $count++;
        }
        return json_encode( [ 'result' => 'success' , 'count' => $count , 'action' => $status ] );
    }

    /**
     * 启用禁用状态切换
     * @param $item
     * @return string
     */
    public static function block_btn_status_select( $item ){

        $url = URL::action( static::$action . '.edit.status' );
        if(  Permission::checkMe( static::$action . '.edit.status' ) ){
            $disabled = "";
        }
        else{
            $disabled = "disabled";
        }
        $current = $item->getStatus();
        $token = Session::token();
        $li = '';
        $allowedStatus = $item->getAvailableNextStatus();
        if( isset( static::$allowedStatus ) && is_array( static::$allowedStatus ) ){
            $allowedStatus = array_intersect( $allowedStatus , static::$allowedStatus );
        }
        foreach( $allowedStatus  as $status ){
            $li .= <<<LI
<li><a class="table-role-btn-switch" href="javascript:;" data-attr-id='{$item->id}' data-attr-token='{$token}' data-attr-url='{$url}' {$disabled}>{$status}</a></li>
LI;
        }
        $button = <<<BUTTON
<div class="btn-group" style="margin:0 auto;">
      <button type="button" class="btn btn-xs btn-warning btn-current-status" {$disabled}>{$current}</button>
      <button type="button" class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown" {$disabled}>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" role="menu">
      {$li}
      </ul>
    </div>
BUTTON;
        return $button;
    }

} 