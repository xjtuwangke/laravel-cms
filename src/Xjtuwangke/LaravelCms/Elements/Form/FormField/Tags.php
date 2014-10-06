<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/25
 * Time: 03:25
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class Tags extends FormFieldBase{

    public function label(){
        return parent::label() . static::tooltip( '标签只能单个提交：输入一个点击一次【增加标签】' , 'exclamation-sign' );
    }

    public function value(){
        $value = parent::value();
        if( ! $value ){
            $value = array();
        }
        return $value;
    }
} 