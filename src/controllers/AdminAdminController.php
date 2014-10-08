<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-22
 * Time: 1:05
 */

namespace Xjtuwangke\LaravelCms\Controllers;

use Xjtuwangke\LaravelCms\Controller\CMSBaseController;
use Xjtuwangke\LaravelCms\Elements\KTable;
use Xjtuwangke\LaravelCms\Elements\Form\FormField\FormFieldBase;
use Xjtuwangke\LaravelCms\Elements\Form\KForm;
use Xjtuwangke\LaravelCms\Elements\KPanel;

use Illuminate\Support\Facades\Hash;
use Xjtuwangke\LaravelModels\Rbac\RoleModel;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSHistoryTrait;

class AdminAdminController extends CMSBaseController {

    use AdminBaseControllerTrait , CMSHistoryTrait;

    protected static $name = '管理员';

    protected static $class = 'AdminUserModel';

    protected $navbar = [];

    protected $paginate = 15;

    protected static $action = 'admin.admin';

    protected static $uri = 'admin/admin';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $model = static::queryAll()->where( 'id' , '!=' , '1' );
        $this->_list( $model );
        $this->layout->title = '管理员列表';
    }

    protected function _init_table(){
        $table = new KTable();
        //$table->title( '管理员列表' );
        $table->th( '姓名' )
            ->searchable( 'username' )
            ->functional( function( $user ){ return e($user->username); } )
            ->th( '工号' )
            ->searchable( 'employee_id' )
            ->functional( function( $user ){ return e($user->employee_id); } )
            ->th( '群组' )
            ->sortable( 'role_id' )
            ->functional( function( $user ){ return e($user->role->title); } )
            ->th( '邮件' )
            ->searchable( 'email' )
            ->functional( function( $user ){ return e($user->email); } )
            ->th( '手机' )
            ->searchable( 'mobile' )
            ->functional( function( $user ){ return e($user->mobile); } )
            ->th( '创建时间' )
            ->sortable( 'created_at' )
            ->functional( function( $user ){ return e($user->created_at); } )
            ->th( '操作' )
            ->functional( function( $user ){ return static::block_btn_groups( $user ); } );
        return $table;
    }

    /**
     * 生成新建或修改表单
     * @param null $item
     * @param int  $id
     * @return KForm
     */
    protected function _form( $id = 0 , $item = null ){

        $roles = RoleModel::all();

        $role_options = [];
        foreach( $roles as $role ){
            $role_options[ $role->id ] = $role->title;
        }

        $form = parent::_form( $id , $item );
        $form->addField( FormFieldBase::createByType( 'username' , FormFieldBase::Type_Text )->setRules( 'required' )->setLabel('请输入用户名')->setCol( 1/3 ) );
        $form->addField( FormFieldBase::createByType( 'password' , FormFieldBase::Type_Text )->setRules( 'required' )->setLabel('请输入密码')->setCol( 1/3 ) );
        $form->addField( FormFieldBase::createByType( 'email' , FormFieldBase::Type_Text )->setRules( 'required|unique:admins,email,{$id}' )->setLabel('请输入邮箱')->setCol( 1/3 ) );
        $form->newRow();

        $form->addField( FormFieldBase::createByType( 'mobile' , FormFieldBase::Type_Text )->setRules( 'required|unique:admins,mobile,{$id}|mobile' )->setLabel('请输入手机')->setCol( 1/2 ) );
        $form->addField( FormFieldBase::createByType( 'employee_id' , FormFieldBase::Type_Text )->setRules( 'required|unique:admins,employee_id,{$id}' )->setLabel('请输入工号')->setCol( 1/2 ) );
        $form->newRow();

        $form->addField( FormFieldBase::createByType( 'avatar' , FormFieldBase::Type_Image )->setLabel('头像')->setCol( 1/2 ) );
        $form->addField( FormFieldBase::createByType( 'role_id' , FormFieldBase::Type_Select )->setOptions($role_options)->setRules( 'required' )->setLabel('请选择角色')->setCol( 1/2 ));
        $form->newRow();

        if( $id ){
            $form->modelToDefault( $item );
            $form->setRules( 'password' , '' );
            $form->setDefault( 'password' , '' );
        }
        else{
        }
        return $form;
    }

    /**
     * 表单验证通过,将$form中的数据储存在数据库中
     * @param KForm $form
     * @param       $item
     * @param int   $id
     * @return mixed
     */
    protected function _store( KForm $form , $item , $id = 0){

        $item->username = $form->value('username');
        $item->employee_id = $form->value('employee_id');
        $item->email = $form->value('email');
        $item->mobile = $form->value('mobile');
        $item->avatar = $form->value('avatar');
        $item->username = $form->value('username');
        if( $id ){
            if( $password = $form->value( 'password' ) ){
                $item->password = Hash::make( $form->value('password') );
            }
        }
        else{
            $item->password = Hash::make( $form->value('password') );
        }
        $item->role_id = $form->value( 'role_id' );
        $item->save();
        //$item = $form->save( $item );
        return $item;
    }

    /**
     * 生成详情页面
     * @param $item
     * @return array  An array of panels
     */
    protected function _show( $item ){

        $panel = new KPanel( KPanel::STYLE_PRIMARY );
        $panel->title( '用户详情' );
        $table = new KTable();
        $table->th( '姓名' )->setWidth( '10%' )
            ->th( '邮件' )->setWidth( '10%' )
            ->th('手机')->setWidth( '10%' )
            ->th('创建时间')->setWidth( '10%' )
            ->th('上次登录时间')->setWidth( '10%' )
            ->td( $item->username )
            ->td( $item->email )
            ->td( $item->mobile )
            ->td( $item->created_at )
            ->td( $item->last_login )
            ->tr();
        $panel->content( $table );

        return [ $panel ];
    }
}