<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 05:11
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;


class DateTime extends FormFieldBase{

    /**
     * 默认时间
     * @var null
     */
    public $defaultDateTime = null;

    public function __construct( $name ){
        parent::__construct( $name );
        $this->setRules( 'date' );
    }

} 