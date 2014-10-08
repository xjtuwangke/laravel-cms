<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-17
 * Time: 17:13
 */


namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Xjtuwangke\LaravelCms\Auth\Permission;

trait CMSButtonsTrait {

    /**
     * @param $item resource
     * @return string buttons in html
     */
    public static function block_btn_groups( $item ){
        return static::block_btn_show( $item ) . static::block_btn_edit( $item ) . static::block_btn_trash( $item ) . static::block_btn_restore( $item ) . static::block_btn_preview( $item );
    }



    /**
     * 生成新增按钮 GET方式跳转新页面
     * @return string
     */
    public static function block_btn_create(){
        $action = static::$action . '.create.form';
        $url = URL::action( $action );
        if(  Permission::checkMe( $action ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        return "<a class='btn btn-sm btn-info gofarms-btn-actions' href='{$url}' target='_blank' {$disabled}><span class='glyphicon glyphicon-plus'></span></a>";
    }

    /**
     * 生成编辑按钮 GET方式跳转新页面
     * @param $item
     * @return string
     */
    public static function block_btn_edit( $item ){
        if( true == $item->trashed() ){
            return '';
        }
        $action = static::$action . '.edit.form';
        if( Permission::checkMe( $action ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        $url = URL::action( $action , $item->id );
        return "<a class='btn btn-sm btn-success gofarms-btn-actions' href='{$url}' {$disabled}><span class='glyphicon glyphicon-pencil'></span></a>";
    }

    /**
     * 生成查看详情按钮 GET方式跳转新页面
     * @param $item
     * @return string
     */
    public static function block_btn_show( $item ){
        $action = static::$action . '.show.detail';
        $url = URL::action( $action , $item->id );
        if(  Permission::checkMe( $action ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        return "<a class='btn btn-sm btn-info gofarms-btn-actions' href='{$url}' target='_blank' {$disabled}><span class='glyphicon glyphicon-search'></span></a>";
    }

    /**
     * @param $item
     * @return string
     */
    public static function block_btn_preview( $item ){
        $url = \HTMLize::create( $item )->url();
        if( 'javascript:;' != $url && $url ){
            return <<<HTML
<a class="btn btn-sm btn-warning gofarms-btn-actions" href="{$url}" target="_blank"><span class="glyphicon glyphicon-arrow-right" data-toggle="tooltip" data-placement="top" title="在网站中查看..."></span></a>
HTML;
        }
        else{
            return '';
        }
    }

    /**
     * 启用禁用状态切换
     * @param $item
     * @return string
     */
    public static function block_btn_switch( $item ){
        if( true == $item->trashed() ){
            return '';
        }
        if( false == method_exists( $item , 'switchOn' ) || false == method_exists( $item , 'switchOff' )){
            return '';
        }
        if( $item->switchIsOn() ){
            $action = static::$action . '.edit.switch.off';
            $checked = 'checked';
        }
        else{
            $action = static::$action . '.edit.switch.on';
            $checked = '';
        }
        $urlON = URL::action( static::$action . '.edit.switch.on' );
        $urlOFF = URL::action( static::$action . '.edit.switch.off' );
        if(  Permission::checkMe( $action ) ){
            $readonly = "";
        }
        else{
            $readonly = "readonly";
        }
        $token = Session::token();
        return "<input type='checkbox' data-on-text='启用' data-off-text='禁用' data-size='small' data-token='{$token}' data-item-id='{$item->id}' data-action-on='{$urlON}' data-action-off='{$urlOFF}' class='gofarms-admin-switch' {$checked} {$readonly}>";
    }

} 