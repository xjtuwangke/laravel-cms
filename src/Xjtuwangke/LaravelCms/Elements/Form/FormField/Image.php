<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/25
 * Time: 02:58
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class Image extends FormFieldBase{

    protected $width = null;

    protected $height = null;

    protected $type = 'default';

    protected $size = null;

    public function __construct( $name ){
        parent::__construct( $name );
        $this->size = '2M';
    }

    public function label(){
        $width = $this->width() ? $this->width() : '不限';
        $height = $this->height() ? $this->height() : '不限';
        $size = $this->size() ? $this->size() : '不限';
        return parent::label() . static::tooltip( "宽度{$width}高度{$height}尺寸{$size}"  , 'picture' );
    }

    public function setType( $type ){
        $this->type = $type;
        return $this;
    }

    public function type(){
        return $this->type;
    }

    public function setWidth( $width ){
        $this->width = $width;
        return $this;
    }

    public function setHeight( $height ){
        $this->height = $height;
        return $this;
    }

    public function setSize( $size ){
        $this->size = $size;
        return $this;
    }

    public function width(){
        return $this->width;
    }

    public function height(){
        return $this->height;
    }

    public function size(){
        return $this->size;
    }

} 