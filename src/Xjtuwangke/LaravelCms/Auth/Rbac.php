<?php

namespace Xjtuwangke\LaravelCms\Auth;

class Rbac {

    public static function checkAdminPermission( $user , $action , $parameters = null ){

        if( in_array( $action , [ 'admin.login' , 'admin.logout' , 'admin.index' , 'admin.lock' , 'admin.unlock' ] ) ){
            return true;
        }
        if( $user ){
            return $user->role->checkPermission( \RoleModel::actionToString( $action , $parameters ) );
        }
        return false;

    }

    public static function checkMyAdminPermission( $action , $parameters = null ){
        return static::checkAdminPermission( \AuthModel::getUser() , $action , $parameters );
    }

    public static function createFarmAdmin( array $attributes , $farm ){
        $attributes['farm_id'] = $farm->id;
        $farm_role = RoleModel::where( 'name' , 'farm' )->first();
        $admin = static::createWithRole( $attributes , $farm_role );
        return $admin;
    }

}
