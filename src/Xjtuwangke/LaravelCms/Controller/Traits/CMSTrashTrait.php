<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-17
 * Time: 23:55
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Xjtuwangke\LaravelCms\Elements\Form\KForm;
use Illuminate\Support\Facades\Route;
use Xjtuwangke\LaravelCms\Elements\Form\FormField\Text;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Xjtuwangke\LaravelCms\Elements\KMessager;
use Xjtuwangke\LaravelCms\Auth\Permission;

trait CMSTrashTrait {

    public static function _routes_trash(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::post( "{$_uri}/trash" , [ 'as' => "{$_as}.delete.trash" , 'uses' => "{$_class}@trash" ] );
        Route::get( "{$_uri}/trashed" , [ 'as' => "{$_as}.delete.trashed" , 'uses' => "{$_class}@trashed" ] );
        Route::post( "{$_uri}/restore" , [ 'as' => "{$_as}.delete.restore" , 'uses' => "{$_class}@restore" ] );
    }

    /**
     * 生成删除单个资源按钮 POST方式跳转新页面
     * @param $item
     * @return string
     */
    public static function block_btn_trash( $item ){
        if( true == $item->trashed() ){
            return '';
        }
        $action = static::$action . '.delete.trash';
        if(  Permission::checkMe( $action ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        $form = new KForm();
        $form->addField(  new Text( 'id[]' ) )->setLabel('')->setDefault( $item->id );
        $form = $form->setMethod('POST')->setAction( $action )->hide()->render() . '</form>';
        return "<a class='btn btn-sm btn-danger btn-submit-form-inside gofarms-btn-actions' data-attr-confirm='确定要删除吗' href='javascript:;' {$disabled}>{$form}<span class='glyphicon glyphicon-trash'></span></a>";
    }

    /**
     * 移出回收站按钮 POST方式跳转新页面
     * @param $item
     * @return string
     */
    public static function block_btn_restore( $item ){
        if( false == $item->trashed() ){
            return '';
        }
        $action = static::$action . '.delete.restore';
        if(  Permission::checkMe( $action ) ){
            $disabled = '';
        }
        else{
            $disabled = 'disabled';
        }
        $form = new KForm();
        $form->addField(  new Text( 'id[]' ) )->setLabel('')->setDefault( $item->id );
        $form = $form->setMethod('POST')->setAction( $action )->hide()->render() . '</form>';
        return "<a class='btn btn-sm btn-warning btn-submit-form-inside gofarms-btn-actions gofarms-btn-remove' data-attr-confirm='确定要恢复吗' href='javascript:;' {$disabled}>{$form}<span class='glyphicon glyphicon-repeat'></span></a>";
    }

    /**
     * 从回收站恢复删除的资源
     * @return Response
     */
    public function restore(){
        //
        $id = Input::get( 'id' );
        $users = static::queryAll()->onlyTrashed()->where( 'id' , $id )->get();
        $count = 0;
        foreach( $users as $user ){
            $count++;
            $user->restore();
        }
        KMessager::push( "将{$count}条数据从回收站恢复" , KMessager::NOTICE );
        return Redirect::action( static::$action . '.show.index' );
    }


    /**
     * 将资源放入回收站
     * @return \Illuminate\Http\RedirectResponse
     */
    public function trash(){
        $id = Input::get( 'id' );
        $count = static::queryAll()->where( 'id' , $id )->delete();
        KMessager::push( "删除了{$count}条数据" , KMessager::NOTICE );
        return Redirect::action( static::$action . '.show.index' );
    }

} 