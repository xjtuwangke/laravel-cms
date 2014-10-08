<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-17
 * Time: 17:14
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Xjtuwangke\LaravelModels\Observer\HistoryModel;
use Xjtuwangke\LaravelCms\Elements\KPanel;
use Xjtuwangke\LaravelCms\Elements\KTable;

trait CMSHistoryTrait {

    /**
     * 展示某一个资源的操作记录
     * @param $item 需要展示历史记录的resource
     * @return KPanel 以panel形式返回操作记录
     */
    public static function adminHistoryPanel( $item ){
        $panel = new KPanel( KPanel::STYLE_WARNING );
        $panel->title( '历史操作记录' );
        $panel->collapse();
        $table = new KTable();

        $table->th( '发生时间' )
            ->functional( function( HistoryModel $record ){ return e($record->created_at); } )
            ->th( '操作者' )
            ->functional( function( HistoryModel $record ){
                $operator = $record->operator;
                if( $operator ){
                    return e( $operator->username );
                }
                else{
                    return '匿名操作';
                }
            })
            ->th( '涉及表格' )
            ->functional( function( HistoryModel $record ){ return e($record->table . '.' . $record->resource_id ); } )
            ->th( '动作' )
            ->functional( function( HistoryModel $record ){ return e($record->action); } )
            ->th( '旧数据' )
            ->functional( function( HistoryModel $record ){
                $records = HistoryModel::unserialize( $record->old );
                $html = '';
                foreach( $records as $key=>$val ){
                    $key = json_encode( $key );
                    $val = json_encode( $val );
                    $html.= <<<HTML
<span class="label label-warning">{$key}:{$val}</span>
HTML;
                    return $html;
                }
            })
            ->th( '新数据' )
            ->functional( function( HistoryModel $record ){
                $records = HistoryModel::unserialize( $record->new );
                $html = '';
                foreach( $records as $key=>$val ){
                    $key = e( $key );
                    $val = e( $val );
                    $html.= <<<HTML
<span class="label label-success">{$key}:{$val}</span>
HTML;
                    return $html;
                }
            });
        $table->itemsToTbody( $item->histories );
        $panel->content( $table );
        return $panel;
    }

}