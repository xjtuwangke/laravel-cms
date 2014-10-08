<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-18
 * Time: 0:00
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Illuminate\Support\Facades\Route;
use Xjtuwangke\LaravelCms\Elements\Form\KForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\URL;
use InvalidArgumentException;
use Illuminate\Support\Facades\Redirect;
use Xjtuwangke\LaravelCms\Elements\KMessager;

trait CMSEditTrait {

    public static function _routes_edit(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::get( "{$_uri}/edit/{id}" , [ 'as' => "{$_as}.edit.form" , 'uses' => "{$_class}@edit" ] );
        Route::post( "{$_uri}/update/{id}" , [ 'as' => "{$_as}.edit.update" , 'uses' => "{$_class}@update" ] );
        Route::get( "{$_uri}/create" , [ 'as' => "{$_as}.create.form" , 'uses' => "{$_class}@create" ] );
        Route::post( "{$_uri}/store" , [ 'as' => "{$_as}.create.store" , 'uses' => "{$_class}@store" ] );
    }

    /**
     * 生成新表单
     * @param null $item
     * @param      $id
     * @return KForm
     */
    protected function _form( $id = 0 , $item = null ){
        $form = new KForm();
        if( $id == 0 ){
            $form->setAction( static::$action . '.create.store' );
        }
        else{
            $form->setAction( [ static::$action . '.edit.update' , $id ] );
        }
        return $form;

        /*
    protected function _form( $id = 0 , $item = null ){
        $form = parent::_form( $id , $item );
        $form->addField( 'username' , 'required' )->setLabel('请输入用户名')->setType(KFormField::Type_Text);
        //...
        if( $id ){
            $form->modelToDefault( $item );
            //...
        }
        else{
            //...
        }
        $form->setSaveFunc( 'avatar' , function( $item , $form , $field ){
            if( null === $item->profile ){
                //ProfileModel::create( ['user_id' => $item->id , 'avatar' => $field->value() ] );
            }
            else{
                $item->profile->avatar = $field->value();
            }
            return $item;
        });
        return $form;
    }
         */

    }

    /**
     *
     * 表单验证通过,将$form中的数据储存在数据库中\
     * @param KForm $form
     * @param       $item
     * @param int   $id
     * @return mixed
     */
    protected function _store( KForm $form , $item , $id = 0){
        $item = $form->save( $item );
        $item->save();
        return $item;
    }


    /**
     * 展示新增表单
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $form = Session::get( 'flashdata_create_form' );
        if( ! $form ){
            $class = static::$class;
            $model = new $class;
            $form = $this->_form( 0 , $model );
            $this->fireCMSControllerEvent( 'creating' , [ $form , $model ] );

        }
        $this->layout->content = View::make( 'laravel-cms::cms/form' )->with( 'form' , $form );
        $this->layout->title = '新建' . static::$name ;
    }

    /**
     * 保存新增数据
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
        $class = static::$class;
        $model = new $class;
        $form = $this->_form();
        if( $form->validation() ){
            // validate ok
            $this->fireCMSControllerEvent( 'storing' , [ $form , $model ] );

            $item = $this->_store( $form , $model , 0 );
            $link = '';
            try{
                $link.= HTML::link( URL::action( static::$action . '.show.detail' ,  [ $item->id ]  ) , '&nbsp;查看详情&nbsp;' , [ 'target' => '_blank'] );
            }
            catch( InvalidArgumentException $e){
                $link.= '';
            }


            $url = \HTMLize::create( $item )->url();
            if( 'javascript:;' != $url && $url ){
                $link.= HTML::link( $url  , '&nbsp;查看网页&nbsp;' , [ 'target' => '_blank'] );
            }

            try{
                $url = URL::action( static::$action . '.edit.form' , [ $item->id ]   ) ;
            }
            catch( InvalidArgumentException $e){
                $url = null;
            }
            if( $url ){
                $link.= HTML::link( $url  , '&nbsp;重新编辑&nbsp;' , [ 'target' => '_blank'] );
            }


            KMessager::push( '添加记录成功' . $link  );
            return Redirect::action( static::$action . '.show.index' );
        }
        else{
            // 以下方式会导致chrome崩溃掉...
            //Session::flash( 'flashdata_create_form' , $form );
            //return Redirect::action( static::$action . '.create' );
            $this->layout->content = View::make( 'laravel-cms::cms/form' )->with( 'form' , $form );
        }
    }

    /**
     * 展示编辑表单
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $form = Session::get( 'flashdata_create_form' );
        if( ! $form ){
            $item = static::queryAll()->find( $id );
            $this->fireCMSControllerEvent( 'editing' , [ $form , $item , $id ] );

            $form = $this->_form( $id , $item );
        }
        $this->layout->content = View::make( 'laravel-cms::cms/form' )->with( 'form' , $form );
        $this->layout->title = '编辑' . static::$name ;
    }


    /**
     * 保存编辑数据
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id )
    {
        //
        $item = static::queryAll()->find( $id );
        if( null === $item ){
            KMessager::push( '找不到对应的数据' , KMessager::ERROR);
            return Redirect::action( static::$action . '.show.index' );
        }
        $form = $this->_form( $id , $item );
        if( $form->validation() ){
            // validate ok
            $this->fireCMSControllerEvent( 'updating' , [ $form , $item , $id ] );

            $this->_store( $form , $item , $id );

            $link = '';
            try{
                $link.= HTML::link( URL::action( static::$action . '.show.detail' ,  [ $item->id ]  ) , '&nbsp;查看详情&nbsp;' , [ 'target' => '_blank'] );
            }
            catch( InvalidArgumentException $e){
                $link.= '';
            }

            $url = \HTMLize::create( $item )->url();
            if( 'javascript:;' != $url && $url ){
                $link.= HTML::link( $url  , '&nbsp;查看网页&nbsp;' , [ 'target' => '_blank'] );
            }

            try{
                $url = URL::action( static::$action . '.edit.form' , [ $item->id ]   ) ;
            }
            catch( InvalidArgumentException $e){
                $url = null;
            }
            if( $url ){
                $link.= HTML::link( $url  , '&nbsp;重新编辑&nbsp;' , [ 'target' => '_blank'] );
            }
            KMessager::push( '更新记录成功' . $link  );
            return Redirect::action( static::$action . '.show.index' );
        }
        else{
            // 以下方式会导致chrome崩溃掉...
            //Session::flash( 'flashdata_create_form' , $form );
            //return Redirect::action( static::$action . '.create' );
            $this->layout->content = View::make( 'laravel-cms::cms/form' )->with( 'form' , $form );
        }
    }

} 