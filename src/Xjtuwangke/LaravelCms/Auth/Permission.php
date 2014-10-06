<?php

namespace Xjtuwangke\LaravelCms\Auth;

use Xjtuwangke\LaravelModels\Rbac\RoleModel;
use Xjtuwangke\LaravelModels\AuthModel;

class Permission {

    public static function check( $user , $action , $parameters = null ){

        if( in_array( $action , [ 'admin.login' , 'admin.logout' , 'admin.index' , 'admin.lock' , 'admin.unlock' ] ) ){
            return true;
        }
        if( $user ){
            return $user->role->checkPermission( RoleModel::actionToString( $action , $parameters ) );
        }
        return false;

    }

    public static function checkMe( $action , $parameters = null ){
        return static::check( AuthModel::getUser() , $action , $parameters );
    }

}
