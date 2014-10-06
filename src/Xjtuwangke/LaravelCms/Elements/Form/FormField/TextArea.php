<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 04:55
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class TextArea extends FormFieldBase{

    /**
     * @var int textarea的rows
     */
    protected $rows = 3;

    /**
     * @param $rows
     * @return $this
     */
    public function setRows( $rows ){
        $this->rows = (int) $rows;
        return $this;
    }

    /**
     * @return int
     */
    public function rows(){
        return $this->rows;
    }
} 