<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/25
 * Time: 06:10
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class Appendable extends FormFieldBase{

    protected $html = '';

    protected $append_url = '';

    public function setHtml( $content ){
        $this->html = $content;
        return $this;
    }

    public function html(){
        return $this->html;
    }

    public function set_append_url( $url ){
        $this->append_url = $url;
        return $this;
    }

    public function append_url(){
        return $this->append_url;
    }
} 