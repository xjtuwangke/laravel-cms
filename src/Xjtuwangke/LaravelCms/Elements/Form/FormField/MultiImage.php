<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/25
 * Time: 03:18
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class MultiImage extends Image{

    /**
     * @return array|null
     */
    public function value(){
        $value = parent::value();
        if( ! $value ){
            $value = array();
        }
        return $value;
    }
} 