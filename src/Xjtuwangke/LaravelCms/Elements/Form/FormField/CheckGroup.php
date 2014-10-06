<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 23:29
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class CheckGroup extends FormFieldBase{

    /**
     * checkbox val=>html对
     * @var array
     */
    protected $options = array();

    /**
     * checkbox 被选中的值
     * @var array
     */
    protected $checked = array();

    /**
     * 获取checkbox的 options
     * @return array
     */
    public function options(){
        return $this->options;
    }

    /**
     * 获取checked
     * @return array
     */
    public function checked(){
        return $this->checked;
    }

    /**
     * @param $options
     * @return $this
     */
    public function setOptions( $options ){
        $this->options = $options;
        return $this;
    }

    /**
     * @param $checked
     * @return $this
     */
    public function setChecked( $checked ){
        $this->checked = $checked;
        return $this;
    }

    /**
     * setChecked的别名
     * @param $selected
     * @return CheckGroup
     */
    public function setSelected( $selected ){
        return $this->setChecked( $selected );
    }

    /**
     * @param $default array
     * @return $this
     */
    public function setDefault( $default ){
        parent::setDefault( $default );
        $this->setChecked( $default );
        return $this;
    }

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