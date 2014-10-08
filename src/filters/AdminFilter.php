<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-7-28
 * Time: 20:14
 */

namespace Xjtuwangke\LaravelCms\Filters;

use Illuminate\Support\Facades\Config;
use Xjtuwangke\LaravelModels\Rbac\RoleModel;
use Xjtuwangke\LaravelModels\AuthModel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use \AdminUserModel;

class AdminFilter {

    protected $forbidden_page = 'admin.forbidden';
    protected $login_page     = 'admin.login';
    protected $action         = null;

    function __construct(){
        Config::set( 'auth' , Config::get( 'laravel-cms::auth_admin' ) );
        RoleModel::registerAllRoles();
    }

    public function filter( \Illuminate\Routing\Route $route , $request ){
        $user = AuthModel::user();
        if( null == $user ){
            //用户未登录
            return Redirect::action( $this->login_page );
        }
        else{
            //用户已登录,检查权限
            if( false == $this->rightsFilter( $user , $route ) ){
                return Redirect::action( $this->forbidden_page );
            }
            else{
                //检查是否锁屏
                $as = Route::current()->getName();
                if( Session::get( 'admin_lock_url' ) &&  ! in_array( $as , [ 'admin.logout' , 'admin.lock' , 'admin.unlock' ] ) ){
                    return Redirect::action( 'admin.lock' );
                }
            }
        }
    }

    public function rightsFilter( $user , \Illuminate\Routing\Route $route ){
        $this->action = $route->getName();
        return AdminUserModel::checkAdminPermission( $user , $this->action , Route::current()->parameters() );
    }

} 