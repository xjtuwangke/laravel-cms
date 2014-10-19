<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 02:23
 */

namespace Xjtuwangke\LaravelCms\Elements\Form;

use \Illuminate\Support\Facades\Form;
use \Illuminate\Support\Facades\Input;
use Xjtuwangke\LaravelCms\Elements\Form\FormField\FormFieldBase;

/**
 * Class KForm
 * @package Xjtuwangke\LaravelKform
 */
class KForm {

    /**
     * 保存表单的所有field
     * @var array
     */
    protected $fields = [];

    /**
     * 保存表单的rows
     * @example:
     *         $rows = array( ['fieldname1' , 'fieldname2'] , ['fieldname3'] );
     * @var array
     */
    protected $rows = [];

    /**
     * form的attributes
     * @var array
     */
    protected $form_options = [];

    /**
     * 表单尾部
     * @var string
     */
    protected $tail = '';

    static function open( $options = [] ){
        if( array_key_exists( 'class' , $options ) ){
            $options['class'].= ' form-horizontal';
        }
        else{
            $options['class'] = 'form-horizontal';
        }
        if( array_key_exists( 'role' , $options ) ){

        }
        else{
            $options['role'] = 'form';
        }
        return \Form::open( $options );
    }

    public function __construct(){
        $this->tail = $this->tail = '<div class="row"><button type="submit" class="btn btn-primary">确定</button></div>' . Form::close();
    }

    public function addField( FormFieldBase $field ){
        $this->fields[ $field->name() ] = $field;
        $field->belongsToForm( $this );
        return $field;
    }

    /**
     * 设置某一行的fields
     * @param int   $number
     * @param array $fields  field名
     * @return $this
     */
    public function setRow( $number , array $fields ){
        $this->rows[ $number ] = $fields;
        return $this;
    }

    public function newRow(){
        $old = array();
        foreach( $this->rows as $row ){
            $old = array_merge( $old , $row );
        }
        $new = array();
        foreach( $this->fields as $field_name => $field ){
            if( !in_array( $field_name , $old ) ){
                $new[] = $field_name;
            }
        }
        $this->rows[] = $new;
        return $this;
    }

    public function setMethod( $method = 'POST' ){
        $this->form_options['method'] = $method;
        return $this;
    }

    public function setAction( $action = null ){
        $this->form_options['action'] = $action;
        return $this;
    }

    public function hide(){
        if( isset( $this->form_options['style'] ) ){
            $this->form_options['style'].= 'display:none;';
        }
        else{
            $this->form_options['style'] = 'display:none;';
        }
        return $this;
    }

    public function removeField( $name ){
        if( array_key_exists( $name , $this->fields ) ){
            unset( $this->fields[ $name ] );
        }
        return $this;
    }

    public function field( $name ){
        if( array_key_exists( $name , $this->fields ) ){
            return $this->fields[ $name ];
        }
        else{
            return null;
        }
    }

    /**
     * 验证
     * @param null $input
     * @return bool
     */
    public function validate( $input = null ){
        if( is_null( $input ) ){
            $input = Input::all();
        }
        $pass = true;
        foreach( $this->fields as $name => $field ){
            if( isset( $input[ $name ] ) ){
                $value = $input[ $name ];
            }
            else{
                $value = null;
            }
            if( false == $field->validate( $value ) ){
                $pass = false;
            };
        }
        return $pass;
    }

    /**
     * validate的别名
     * @param null $input
     * @return bool
     */
    public function validation( $input = null ){
        return $this->validate( $input );
    }

    /**
     * 得到某个表单域的值
     * @param $name
     * @return null
     */
    public function value( $name ){
        if( $field = $this->field( $name ) ){
            return $field->value();
        }
        else{
            return null;
        }
    }

    /**
     * 将Model数据设置为表单的default值
     * @param $model
     */
    public function modelToDefault( $model ){
        foreach( $this->fields as $field ){
            $value = $this->getModelValueAttribute( $field->name() , $model );
            if( ! is_null( $value ) ){
                $field->setDefault( $value );
            }
        }
    }

    /**
     * Get the model value that should be assigned to the field.
     *
     * @param $name
     * @param $model
     * @return mixed
     */
    protected function getModelValueAttribute($name , $model )
    {
        if (is_object($model))
        {
            return object_get($model, $this->transformKey( $name ) );
        }
        elseif (is_array($model))
        {
            return array_get($model, $this->transformKey( $name ) );
        }
    }

    /**
     * Transform key from array to dot syntax.
     *
     * @param  string  $key
     * @return string
     */
    protected function transformKey($key)
    {
        return str_replace(array('.', '[]', '[', ']'), array('_', '', '.', ''), $key);
    }

    /**
     * 自定义表单尾部按钮
     * @param $tail
     * @return $this
     */
    public function setTail( $tail ){
        $this->tail = $tail;
        return $this;
    }

    /**
     * draw a form
     * @return mixed
     */
    public function render(){
        $form = static::open( $this->form_options );

        $displayed = array();

        foreach( $this->rows as $row ){
            $form.= '<div class="row">';
            foreach( $row as $field_name ){
                $field = $this->field( $field_name );
                if( $field ){
                    $form.= $field->render();
                    $displayed[] = $field_name;
                }

            }
            $form.= '</div>';
        }

        foreach( $this->fields as $field_name => $field  ){
            if( ! in_array( $field_name , $displayed ) ){
                $form.= '<div class="row">';
                $form.= $field->render();
                $form.= '</div>';
            }
        }
        $form.= $this->tail;
        return $form;
    }

    /**
     * @param      $name
     * @param null $func
     * @return $this
     */
    public function setSaveFunc( $name , $func = null ){
        $field = $this->field( $name );
        if( $field ){
            $field->setSaveFunc( $func );
        }
        return $this;
    }

    /**
     * 将formfield中的数据存入item
     * @param $item
     * @return mixed
     */
    public function save( $item ){
        foreach( $this->fields as $field ){
            $field->valueToModel( $item , $this );
        }
        return $item;
    }

    /**
     * @param $name
     * @param $rules
     * @return $this
     */
    public function setRules( $name , $rules ){
        $field = $this->field( $name );
        if( $field ){
            $field->setRules( $rules );
        }
        return $this;
    }

    /**
     * @param      $name
     * @param bool $hide
     * @return $this
     */
    public function setHide( $name , $hide = true ){
        $field = $this->field( $name );
        if( $field ){
            $field->setHide( $hide );
        }
        return $this;
    }

    /**
     * @param $name
     * @param $fixed
     * @return $this
     */
    public function setFixed( $name , $fixed ){
        $field = $this->field( $name );
        if( $field ){
            $field->setFixed( $fixed );
        }
        return $this;
    }

    /**
     * @param $name
     * @param $default
     * @return $this
     */
    public function setDefault( $name , $default ){
        $field = $this->field( $name );
        if( $field ){
            $field->setDefault( $default );
        }
        return $this;
    }

    /**
     * @param $name
     * @param $col
     * @return $this
     */
    public function setCol( $name , $col ){
        $field = $this->field( $name );
        if( $field ){
            $field->setCol( $col );
        }
        return $this;
    }

    /**
     * @param $name
     * @param $error
     * @return $this
     */
    public function set_error( $name , $error ){
        $field = $this->field( $name );
        if( $field ){
            $field->pushError( $error );
        }
        return $this;
    }
}