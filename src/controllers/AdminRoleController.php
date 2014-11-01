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

use Illuminate\Support\Facades\Hash;
use Xjtuwangke\LaravelModels\Rbac\RoleModel;
use Xjtuwangke\LaravelCms\Controller\Traits\CMSHistoryTrait;

use Illuminate\Support\Facades\Input;

class AdminRoleController extends CMSBaseController {

    use AdminBaseControllerTrait , CMSHistoryTrait;

    protected static $name = '管理员角色';

    protected static $class = 'Xjtuwangke\LaravelModels\Rbac\RoleModel';

    protected $navbar = [];

    protected $paginate = 15;

    protected static $action = 'admin.role';

    protected static $uri = 'admin/role';


    public static function block_btn_edit( $item ){
        if( $item->name == 'root' || $item->name == 'farm' ){
            return '';
        }
        else{
            return parent::block_btn_edit( $item );
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $model = static::queryAll()->where( 'name' , '!=' , 'root' )->where( 'name' , '!=' , 'farm' );
        $this->_list( $model );
        $this->layout->title = '查看' . static::$name;
    }

    protected function _init_table(){
        $table = new KTable();
        //$table->title( '管理员角色列表' );
        $table->th( '角色名' )
            ->searchable( 'name' )
            ->functional( function( $role ){ return e($role->name); } )
            ->th( '描述' )
            ->searchable( 'title' )
            ->functional( function( $role ){ return e($role->title); } )
            ->th( '操作' )
            ->functional( function( $role ){ return static::block_btn_groups( $role ); } );
        return $table;
    }

    protected function _rights( $role , $field ){
        if( ! $role || ! $role->name ){
            return [];
        }
        $array = [ 'show.*' , 'edit.*' , 'create.*' , 'delete.*' ];
        $result = [];
        foreach( $array as $one ){
            //KUserRights::hasRight( $admin ,    )
            $action = $field . '.' . $one;
            if( $role->checkPermission( $action ) ){
                $result[] = $one;
            }
        }
        return $result;
    }

    protected function _rightsForm( $form , $item ){
        $form->addField( FormFieldBase::createByType( 'rights[customer]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '用户管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.customer' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[issue]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '客服权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.issue' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[farm]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '农场管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.farm' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[farmactivity]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '农场活动权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.farmactivity' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[goods]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '商品管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.goods' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[order]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '订单管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.order' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[article]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '文章管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.article' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[cookbook]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '菜谱管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.cookbook' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[logistics]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '仓储管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' , 'create.*' => '新建' , 'delete.*' => '删除' ] )
                ->setSelected( $this->_rights( $item , 'admin.logistics' ) )
        );

        $form->addField( FormFieldBase::createByType( 'rights[payments]' , FormFieldBase::Type_CheckGroup )
                ->setLabel( '支付管理权限' )
                ->setOptions( [ 'show.*' => '查看' , 'edit.*' => '编辑' ] )
                ->setSelected( $this->_rights( $item , 'admin.payments' ) )
        );
        return $form;
    }

    /**
     * 生成新建或修改表单
     * @param null $item
     * @param int  $id
     * @return KForm
     */
    protected function _form( $id = 0 , $item = null ){
        $form = parent::_form( $id , $item );

        $form->addField( FormFieldBase::createByType( 'name' , FormFieldBase::Type_Text )->setLabel('展示名称')->setRules( 'required' ) );
        $form->addField( FormFieldBase::createByType( 'title' , FormFieldBase::Type_Text )->setLabel('职务')->setRules( 'required' ) );
        $form->addField( FormFieldBase::createByType( 'desc' , FormFieldBase::Type_Text )->setLabel('角色简述')->setRules( 'required' ) );

        if( $item && $item->isRoot() ){

        }
        else{
            $form = $this->_rightsForm( $form  , $item );
        }

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
     * @param $role
     * @param int   $id
     * @return mixed
     */
    protected function _store( KForm $form , $role , $id = 0){

        $rights = Input::get( 'rights' );
        $admin_rights = [];
        foreach( $rights as $k1 => $v1 ){
            foreach( $v1 as $k2 => $v2 ){
                $admin_rights[] = "admin.{$k1}.{$v2}";
            }
        }

        if( $id ){
            $role->title = $form->value('title');
            $role->name = $form->value('name');
            $role->desc = $form->value('desc');
            $role->setPermissions( $admin_rights );
            $role->save();
        }
        else{
            $role = RoleModel::create( array(
                'title' => $form->value( 'title' ),
                'name'  => $form->value( 'name' ),
                'desc'  => $form->value( 'desc' ),
            ));
            $role->setPermissions( $admin_rights );
            $role->save();
        }

        return $role;
    }

    /**
     * 生成详情页面
     * @param $item
     * @return array  An array of panels
     */
    protected function _show( $item ){
        return [];
    }
}