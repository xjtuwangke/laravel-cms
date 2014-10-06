<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-7-29
 * Time: 2:17
 */

namespace Xjtuwangke\LaravelCms\Controller;

use Xjtuwangke\LaravelCms\Controller\Traits\CMSDetailTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSListTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSEditTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSButtonsTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSSwitchTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSTrashTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\ControllerEventTrait;
use Xjtuwangke\LaravelCms\Controller\Traits\AdminBaseControllerTrait;


class CMSBaseController extends \Controller {

    use ControllerEventTrait;

    use AdminBaseControllerTrait , CMSDetailTrait , CMSListTrait , CMSEditTrait , CMSButtonsTrait , CMSSwitchTrait , CMSTrashTrait;

    protected static $name = '未知';

    protected static $class = null;

    protected $navbar = [];

    protected $paginate = 15;

    protected static $action = null;

    protected static $uri = null;

    private static $controller_model_relationship = [];

    public static function queryAll(){
        $class = static::$class;
        return $class::where( 'id' , '!=' , '-1' );
    }


    /**********下面是框架部分,基本不需要重写************************************
     * index  Display a listing of resource
     * trashed 显示回收站中的资源
     * create Show the form for creating a new resource.
     * store  Store a newly created resource in storage.
     * show   Display the specified resource.
     * edit   Show the form for editing the specified resource.
     * update Update the specified resource in storage.
     * destroy Remove the specified resource from storage.
     * switchON 切换到启用状态
     * switchOFF 切换到禁用状态
     ************************************************************************/

    public static function _routes_destroy(){
        //暂未用到
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //暂时没有用到
        static::queryAll()->withTrashed()->find( $id )->forceDelete();
    }

    public static function getName(){
        return static::$name;
    }

    public static function getModel(){
        return static::$class;
    }

    static public function registerRoutes(){
        $class = get_called_class();
        $ref = new \ReflectionClass( $class );
        $methods = $ref->getMethods( \ReflectionMethod::IS_STATIC );
        foreach( $methods as $method ){
            if( preg_match( '/^_routes_.*/' , $method->name ) ){
                $name = $method->name;
                static::$name();
            }
        }
        CMSBaseController::registerController( $class );
    }

    final public static function registerController( $controller ){
        static::$controller_model_relationship[$controller] = [ 'class' => $controller::getName() , 'model' => $controller::getModel() ];
    }

    final public static function getModelMetaData( $model ){
        $array = static::$controller_model_relationship;
        foreach( $array as $controller => $detail ){
            if( $detail[ 'model' ] === $model ){
                return $controller;
            }
        }
        return null;
    }

    public function fireCMSControllerEvent( $name , $parameters = array() ){
        $event = static::$action . '.' . $name;
        return $this->fireControllerEvent( $event , $parameters );
    }

}