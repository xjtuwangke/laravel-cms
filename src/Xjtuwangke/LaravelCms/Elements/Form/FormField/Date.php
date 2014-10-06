<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 05:03
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class Date extends FormFieldBase{


    /**
     * 默认日期
     * @var null
     */
    public $defaultDate = null;

    public function __construct( $name ){
        parent::__construct( $name );
        $this->setRules( 'date' );
    }

}