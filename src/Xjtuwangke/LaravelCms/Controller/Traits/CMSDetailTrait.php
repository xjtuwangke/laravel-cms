<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-18
 * Time: 0:06
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

trait CMSDetailTrait {

    /**
     * 生成详情页面
     * @param $item
     * @return array  An array of panels
     */
    protected function _show( $item ){
        $panel = new KPanel( 'primary' );
        return [ $panel ];
    }

    public static function _routes_show(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::get( "{$_uri}/show/{id}" , [ 'as' => "{$_as}.show.detail" , 'uses' => "{$_class}@show" ] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
        $item = static::queryAll()->withTrashed()->find( $id );
        $this->fireCMSControllerEvent( 'showing' , [ $item , $id ] );
        $panels = $this->_show( $item );
        $this->layout->content = View::make( 'cms/panels' )->with( 'panels' , $panels );

        $url = HTMLize::create( $item )->url();
        if( 'javascript:;' != $url && $url ){
            $shortcut = new stdClass();
            $shortcut->url = $url;
            $shortcut->title = '网页中查看';
            $this->layout->shortcuts[] = $shortcut;
        }

        try{
            $url = URL::action( static::$action . '.edit.form' , [ $id ]   ) ;
        }
        catch( InvalidArgumentException $e){
            $url = null;
        }
        if( $url ){
            $shortcut = new stdClass();
            $shortcut->url = $url;
            $shortcut->title = '打开编辑';
            $this->layout->shortcuts[] = $shortcut;
        }
        $this->layout->title = static::$name . '详情';
    }

} 