<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-10
 * Time: 4:35
 */

namespace Xjtuwangke\LaravelCms\Elements;

class KTable {

    protected $thead = [];

    protected $tbody = [];

    protected $tr = [];

    protected $attributes = [ 'class' => 'table table-hover table-bordered' , 'data-role' => 'table'];

    protected $current_sort_status = [];

    protected $current_search_keywords = [];

    protected $current_group_status = [];

    protected $queries = [];

    protected $queryFunc = null;

    protected $title = '';

    protected $tail = '';

    public function __construct(){
        $this->attributes = HTML::attributes( $this->attributes );
    }

    /**
     * 设定表格的html属性
     * @param array $attributes
     * @return $this
     */
    public function attributes( $attributes = [] ){
        $this->attributes = HTML::attributes( $attributes );
        return $this;
    }

    /**
     * 添加单个thead
     * @param       $html
     * @param array $attributes
     * @param null  $field
     * @param bool  $searchable
     * @param bool  $sortable
     * @param bool  $groupable
     * @param null  $func
     * @return $this
     */
    public function th( $html , $attributes = [] , $field = null , $searchable = false , $sortable = false , $groupable = false , $func = null ){
        $head = new stdClass();
        $head->html = e( $html );
        $head->attributes = HTML::attributes( $attributes );
        $head->field = $field;
        $head->searchable = $searchable;
        $head->sortable = $sortable;
        $head->groupable = $groupable;
        $head->func = $func;
        $head->sortFunc = null;
        $head->groupFunc = null;
        $head->searchFunc = null;
        $this->thead[] = $head;
        return $this;
    }

    /**
     * @param $field
     * @return null
     */
    public function getTh( $field ){
        foreach( $this->thead as $th ){
            if( $th->field == $field ){
                return $th;
            }
        }
        return null;
    }

    /**
     * 将最新添加的thead设置为可排序
     * @param null $field
     * @return $this
     */
    public function sortable( $field = null ){
        $th = end( $this->thead );
        $th->sortable = true;
        if( $field ){
            $th->field = $field;
        }
        return $this;
    }

    /**
     * 设置排序处理函数
     * @param callable $func
     * @return $this
     */
    public function setSortFunc( callable $func ){
        $th = end( $this->thead );
        $th->sortFunc = $func;
        return $this;
    }

    /**
     * 将最新添加的thead设置为可搜索
     * @param null $field
     * @return $this
     */
    public function searchable( $field = null ){
        $th = end( $this->thead );
        $th->searchable = true;
        if( $field ){
            $th->field = $field;
        }
        return $this;
    }

    /**
     * 设置搜索处理函数
     * @param callable $func
     * @return $this
     */
    public function setSearchFunc( callable $func ){
        $th = end( $this->thead );
        $th->searchFunc = $func;
        return $this;
    }

    /**
     * 将最新添加的thead设置为可分组查看
     * @param $field
     * @param $options
     * @return $this
     */
    public function groupable( $field , $options ){
        $th = end( $this->thead );
        $th->field = $field;
        $th->groupable = array( 'field' => $field , 'options' => $options );
        return $this;
    }

    /**
     * 设置分组处理函数
     * @param callable $func
     * @return $this
     */
    public function setGroupFunc( callable $func ){
        $th = end( $this->thead );
        $th->groupFunc = $func;
        return $this;
    }

    /**
     * 设置最新添加的thead的宽度
     * @param $width
     * @return $this
     */
    public function setWidth( $width ){
        $th = end( $this->thead );
        $th->attributes.= " width='$width' ";
        return $this;
    }

    /**
     * 设置最新添加的thead的模型映射函数
     * @param $func
     * @return $this
     */
    public function functional( callable $func ){
        $th = end( $this->thead );
        $th->func = $func;
        return $this;
    }

    /**
     * 绑定query处理函数
     * @param callable $func
     * @return $this
     */
    public function setQueryFunc( $func = null ){
        if( is_callable( $func ) ){
            $this->queryFunc = $func;
        }
        return $this;
    }

    /**
     * 处理query的函数
     * @param $model
     * @param $query
     * @return mixed
     */
    public function defaultQueryFunc( $model , $query ){

        $sort = array_key_exists( 'sort' , $query ) ? $query['sort'] : null ;
        $order = array_key_exists( 'order' , $query ) ? $query['order'] : null ;
        $search = array_key_exists( 'search' , $query ) ? $query['search'] : null ;
        $field  = array_key_exists( 'field' , $query ) ? $query['field'] : null ;
        $groupby = array_key_exists( 'groupby' , $query ) ? $query['groupby'] : null ;
        $group_value = array_key_exists( 'group_value' , $query ) ? $query['group_value'] : null ;

        $q = [];
        if( $sort ){
            if( $th = $this->getTh( $sort ) ){
                $func = $th->sortFunc;
                if( is_callable( $func ) ){
                    $model = $func( $model , $order );
                }
                else{
                    $model = $model->orderBy( $sort , $order );
                }
            }
            $q['sort'] = $sort;
            $q['order'] = $order;
            $this->setSortStatus( $sort , $order );
        }
        else{
            $q['sort'] = 'created_at';
            $q['order'] = 'desc';
            $this->setSortStatus( 'created_at' , 'desc' );
            $model = $model->orderBy( 'created_at' , 'desc' );
        }
        if( $search && $field ){
            if( $th = $this->getTh( $field ) ){
                $func = $th->searchFunc;
                if( is_callable( $func ) ){
                    $model = $func( $model , $search );
                }
                else{
                    $model = $model->where( $field , 'like' , "%$search%" );
                }
            }
            $q['search'] = $search;
            $q['field'] = $field;
            $this->setSearchKeyword( $field , $search );
        }
        if( $groupby && $group_value ){
            if( '*' === $group_value ){
                //do nothing
            }
            else{
                if( $th = $this->getTh( $groupby ) ){
                    $func = $th->groupFunc;
                    if( is_callable( $func ) ){
                        $model = $func( $model , $group_value );
                    }
                    else{
                        $model = $model->where( $groupby , $group_value );
                    }
                }
            }
            $q['groupby'] = $groupby;
            $q['group_value'] = $group_value;
            $this->setGroupStatus( $groupby , $group_value );
        }
        $this->queries = $q;
        return $model;
    }

    /**
     * 进行特定查询工作
     * @param       $model
     * @param array $query
     * @return mixed
     */
    public function query( $model , $query = [] ){
        $func = $this->queryFunc;
        //$this->queries = $query;
        if( is_callable( $func ) ){
            return $func( $model , $query );
        }
        else{
            return $this->defaultQueryFunc( $model , $query );
        }
    }

    /**
     * 获取生效的查询参数
     * @return array
     */
    public function getQueryArray(){
        return $this->queries;
    }

    /**
     * 得到某个特定的thead
     * @param $name
     * @return null | $thead
     */
    public function getThead( $name ){
        if( array_key_exists( $name , $this->thead ) ){
            return $this->thead[ $name ];
        }
        else{
            return null;
        }
    }

    /**
     * 标题区域
     * @param null $title
     * @param bool $xss
     * @return $this|string
     */
    public function title( $title = null , $xss = true ){
        if( null != $title ){
            if( false != $xss ){
                $this->title = e( $title );
            }
            else{
                $this->title = $title;
            }
            return $this;
        }
        else{
            return $this->title;
        }
    }

    /**
     * 设置单个td的值
     * @param       $html
     * @param array $attributes
     * @param bool  $xss
     * @return $this
     */
    public function td( $html , $attributes = [] , $xss = true ){
        $td = new stdClass();
        if( false == $xss ){
            $td->html = $html;
        }
        else{
            $td->html = e( $html );
        }

        $td->attributes = HTML::attributes( $attributes );
        $this->tr[] = $td;
        return $this;
    }

    /**
     * 表格换行
     * @param array $attributes
     * @return $this
     */
    public function tr( $attributes = [] ){
        $tr = new stdClass();
        $tr->attributes = HTML::attributes( $attributes );
        $tr->children = $this->tr;
        $this->tbody[] = $tr;
        $this->tr = [];
        return $this;
    }

    public function setTail( $html = '' ){
        $this->tail = $html;
        return $this;
    }


    public function setSortStatus( $field , $order ){
        $this->current_sort_status[ $field ] = strtolower( $order );
        return $this;
    }

    public function setSearchKeyword( $field , $keyword ){
        $this->current_search_keywords[ $field ] = e( $keyword );
        return $this;
    }

    public function getSearchKeyword( $field ){
        if( array_key_exists( $field , $this->current_search_keywords ) ){
            return $this->current_search_keywords[$field];
        }
        else{
            return '';
        }
    }

    public function setGroupStatus( $field , $selected ){
        $this->current_group_status[ $field ] = $selected;
        return $this;
    }

    public function getGroupStatus( $field ){
        if( array_key_exists( $field , $this->current_group_status ) ){
            return $this->current_group_status[ $field ];
        }
        else{
            return null;
        }
    }

    public function isGroupSelected( $field , $name ){
        if( array_key_exists( $field , $this->current_group_status ) && $name == $this->current_group_status[ $field ] ){
            return true;
        }
        else{
            return false;
        }
    }

    protected function isSortButtonActived( $field , $order ){
        if( array_key_exists( $field , $this->current_sort_status ) && strtolower( $order ) == $this->current_sort_status[ $field ]){
            return true;
        }
        else{
            return false;
        }
    }

    public function itemsToTbody( $items ){
        if( null == $items ){
            return;
        }
        foreach( $items as $item ){
            foreach( $this->thead as $th ){
                $func = $th->func;
                if( is_callable( $func ) ){
                    $this->td( $func( $item ) , [] , false );
                }
                else{
                    $this->td( $func );
                }
            }
            $this->tr();
        }
    }

    public function draw(){
        $thead = '';
        foreach( $this->thead as $th ){
            $thead.= $this->template_th( $th );
        }
        $tbody = '';
        foreach( $this->tbody as $tr ){
            $tbody.="<tr {$th->attributes}>";
            foreach( $tr->children as $td ){
                $tbody.= "<td {$td->attributes}>{$td->html}</td>\n";
            }
            $tbody.='</tr>';
        }

        $table = "
<table {$this->attributes}>
    <thead>
    {$thead}
    </thead>
    <tbody>
    {$tbody}
    </tbody>
</table>";
        $table.= $this->tail;
        return $table;
    }

    public function __toString(){
        return $this->draw();
    }

    public function template_th( $th ){

        if( true == $th->searchable ){
            $keyword = $this->getSearchKeyword( $th->field );
            $search = <<<SEARCHING
<div class="row">
    <div class="input-group input-sm" style="width:200px;margin:0 auto;">
    <input class="form-control input-sm input-table-search" value="{$keyword}" attr-field="{$th->field}" type="text" placeholder="输入关键词搜索">
        <div class="input-group-addon input-sm table-search-btn"><span class="glyphicon glyphicon-search"></span></div>
        <div class="input-group-addon input-sm table-reset-search-btn"><span class="glyphicon glyphicon-remove"></span></div>
    </div>
</div>
SEARCHING;
            $th->html.= $search;
        }

        if( true == $th->sortable ){
            $asc = $this->isSortButtonActived( $th->field , 'asc' )?'btn-success':'btn-default';
            $desc = $this->isSortButtonActived( $th->field , 'desc' )?'btn-success':'btn-default';
            $order_btn = <<<BUTTONS
<div class="btn-group btn-group-xs table-sort-btn" attr-field="{$th->field}">
    <button attr-btn-sort="asc" type="button" class="btn {$asc}" table-role><span class="glyphicon glyphicon-arrow-down"></span></button>
    <button attr-btn-sort="desc" type="button" class="btn {$desc}"><span class="glyphicon glyphicon-arrow-up"></span></button>
</div>
BUTTONS;
            $th->html.= $order_btn;
        }

        if( is_array( $th->groupable ) ){
            $field = $th->groupable['field'];
            $options = $th->groupable['options'];
            $dropdown = '';
            $first = null;
            foreach( $options as $key => $val ){
                if( null === $first ){
                    $first = $val;
                }
                $dropdown.= "<li><a class='btn-table-groupby' attr-field='{$field}' attr-groupby='{$key}' href='javascript:;'>{$val}</a></li>";
            }
            $current = $this->getGroupStatus( $field );
            if( ! $current ){
                $current = $first;
            }
            else{
                $current = $options[ $current ];
            }
            $group_btn = <<<GROUP
<div class="row">
    <div class="btn-group" style="margin:0 auto;">
      <button type="button" class="btn btn-xs btn-success">{$current}</button>
      <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" role="menu">
        {$dropdown}
      </ul>
    </div>
</div>
GROUP;
            $th->html.= $group_btn;
        }

        return "<th {$th->attributes}>{$th->html}</th>\n";
    }

    public static function imageInsideTd( $src ){
        return "<img src='{$src}' class='img img-responsive gofarms-admin-img' style='float:left;'>";
    }

    public static function textInsideTd( $text , $length = 15 , $xss = true ){
        if( $xss ){
            $text = e( $text );
        }
        if( mb_strlen( $text ) > $length ){
            $full = $text;
            $full = str_replace( "\"" , "&quot" , $full );
            $text = mb_substr( $text , 0 , $length ) . '...';
            $text.= "<span class='glyphicon glyphicon-info-sign' data-toggle='tooltip' data-placement='left' title=\"{$full}\"></span>";
        }
        return $text;
    }
}