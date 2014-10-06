<?php

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Xjtuwangke\LaravelModels\AuthModel;
use Xjtuwangke\LaravelCms\Auth\AdminMenu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

trait AdminBaseControllerTrait{

    protected function setupLayout()
    {
        $this->layout = View::make('laravel-cms::layouts/admin-lte');
        $this->layout->content = '';
        $this->layout->title = '';
        $this->layout->site_name = Config::get( 'site.site_name' );
        $this->layout->css = [];
        $this->layout->js  = [];

        $user = AuthModel::getUser();
        $menu = new AdminMenu( $user );

        if( isset( static::$menu_name ) ){
            $menu_name = static::$menu_name;
        }
        elseif( isset( static::$name ) ){
            $menu_name = static::$name;
        }
        else{
            $menu_name = null;
        }

        $this->layout->shortcuts = $menu->getCurrentSubMenu( $menu_name );

        $menu = $menu->getMenu();

        $this->layout->navbar = View::make( 'laravel-cms::admin-lte/navbar' )->with( 'menu' , $menu );
        $this->layout->usermenu = View::make( 'laravel-cms::admin-lte/user_menu' )->with( 'user' , AuthModel::getUser() );
    }
}
