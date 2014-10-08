<?php

namespace Xjtuwangke\LaravelCms\Controllers;

use Xjtuwangke\LaravelModels\AuthModel;
use Illuminate\Routing\Controller;
use Xjtuwangke\LaravelCms\Controller\Traits\AdminBaseControllerTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Xjtuwangke\LaravelCms\Elements\Form\FormField\FormFieldBase;
use Xjtuwangke\LaravelCms\Elements\Form\KForm;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller{

    use AdminBaseControllerTrait;

    public function __construct(){
        Config::set( 'auth' , Config::get( 'laravel-cms::auth_admin' ) );
    }

    public function index(){
        $this->layout->content= View::make( 'laravel-cms::admin-lte/index' );
        $this->layout->title = '首页';
        //$this->layout->analytics = View::make( 'laravel-cms::parts/google_analytics' , [ 'id' => 'UA-1234-5' ] );
    }

    public function lock( $error = false ){
        $this->layout = null;
        if( ! Session::get( 'admin_lock_url' ) ){
            $referer = $_SERVER['HTTP_REFERER'];
            Session::set( 'admin_lock_url' , $referer );
        }
        return View::make( 'laravel-cms::admin-lte/lock' )->with( 'user' , AuthModel::getUser() )->with( 'error' , $error );
    }

    public function unlock(){
        $referer = Session::get( 'admin_lock_url' );
        $this->layout = null;
        $pass = Input::get( 'password' );
        $hash = AuthModel::getUser()->password;
        if( Hash::check( $pass , $hash ) ){
            Session::set( 'admin_lock_url' , null );
            return Redirect::to( $referer );
        }
        else{
            return $this->lock( true );
        }
    }

    public function login(){

        Session::set( 'admin_lock_url' , null );
        $loginForm = new KForm();
        $loginForm->addField( FormFieldBase::createByType( 'login' , FormFieldBase::Type_Text )->setRules( 'required' )->setLabel('请输入工号') );
        $loginForm->addField( FormFieldBase::createByType( 'password' , FormFieldBase::Type_Password )->setRules( 'required' )->setLabel('请输入密码') );

        if( AuthModel::user() !== null ){
            return Redirect::action( 'admin.index' );
        }

        if(  Request::isMethod('POST') ){  //是管理员登陆请求
            if( $loginForm->validation() ){
                $login = $loginForm->value( 'login' );
                $password = $loginForm->value( 'password' );
                if( AuthModel::attempt( [ 'employee_id' => $login , 'password' => $password ] ) ){
                    $admin = AuthModel::getUser();
                    $admin->last_login = new \Carbon\Carbon();
                    $admin->save();
                    return Redirect::action( 'admin.index' );
                }
                else{
                    $loginForm->set_error( 'password' , '错误的用户名或密码' );
                }
            }
            else{
                //
            }
        }
        $this->layout = View::make( 'laravel-cms::admin-lte/login' )->with( 'form' , $loginForm );
    }

    public function logout(){
        Auth::logout();
        return Redirect::action( 'admin.index' );
    }

}
