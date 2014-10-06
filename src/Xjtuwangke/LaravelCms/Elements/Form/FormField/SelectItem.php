<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/25
 * Time: 00:05
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class SelectItem extends FormFieldBase{

    /**
     * 负责该item的Controller类名
     * @var null
     */
    protected $controllorClass = null;

    /**
     * item_id
     * @var null
     */
    protected $itemId = null;

    /**
     * 设置负责该item的Controller类名
     * @param $controller string
     * @return $this
     */
    public function setController( $controller ){
        $this->controllorClass = $controller;
        return $this;
    }

    /**
     * @param $default
     * @return $this
     */
    public function setDefault( $default ){
        parent::setDefault( $default );
        $this->setItemId( $default );
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setItemId( $id ){
        $this->itemId = $id;
        return $this;
    }

    public function controllerClass(){
        return $this->controllorClass;
    }

    public function itemId(){
        return $this->itemId;
    }
} 