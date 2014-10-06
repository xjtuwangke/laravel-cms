<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-17
 * Time: 23:57
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

trait CMSListTrait {

    public static function _routes_index(){
        $_class = get_called_class();
        $_uri = static::$uri;
        $_as  = static::$action;
        Route::get( "{$_uri}/index" , [ 'as' => "{$_as}.show.index" , 'uses' => "{$_class}@index" ] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $model = static::queryAll();
        $this->fireCMSControllerEvent( 'indexing' , [ $model ] );

        $this->_list( $model );
        $this->layout->title = static::$name . '列表';
    }

    /**
     * 回收站里的数据
     *
     * @return Response
     */
    public function trashed(){
        $model = static::queryAll()->onlyTrashed();
        $this->fireCMSControllerEvent( 'trashing' , [ $model ] );
        $this->_list( $model );
        $this->layout->title = static::$name . '回收站';
    }

    /**
     * 初始化表格
     * @return KTable
     */
    protected function _init_table(){
        return new KTable();
    }

    /**
     * 处理paginate并生成视图
     * @param $model
     */
    protected function _list( $model ){
        $table = $this->_init_table();
        $model = $table->query( $model , Input::all() );
        $q = $table->getQueryArray();
        $items = $model->paginate( $this->paginate );
        $this->_index_table( $table , $items );
        $paginate = $items->appends( $q )->links();
        $this->layout->content = View::make( 'cms/table' )->with('table' , $table )->with( 'pagination' , $paginate )->with( 'q' , $q );
    }

    /**
     * 产生列表页的列表
     * @param $table KTable()
     * @param $items Illuminate\Pagination\Paginator 分页排序后的详细数据
     */
    protected function _index_table( KTable $table , $items ){
        $table->itemsToTbody( $items );
    }

} 