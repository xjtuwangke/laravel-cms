<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 02:25
 */

namespace Xjtuwangke\LaravelCms\Elements\Form\FormField;

use Xjtuwangke\LaravelCms\Elements\Form\KForm;
use Xjtuwangke\LaravelCms\Elements\Form\KValidator;
use \Illuminate\Support\Facades\HTML;

/**
 * 表单域基类
 * @class FormFieldBase
 * @package KForm\FormField
 */
class FormFieldBase {


    /**
     * 表单类型
     */
    const Type_Text = 1;
    const Type_Password = 2;
    const Type_TextArea = 3;
    const Type_CheckGroup = 4;
    const Type_RadioGroup = 5;
    const Type_Select = 6;
    const Type_MultiSelect = 7;
    const Type_Image = 8;
    const Type_MultiImage = 9;
    const Type_Tags = 10;
    const Type_Article = 11;
    const Type_Date = 12;
    const Type_SelectItem = 13;
    const Type_DateTime = 14;
    const Type_DateRange = 15;
    const Type_Appendable = 16;

    /**
     * @var bool 是否隐藏
     */
    protected $hide = false;

    /**
     * @var bool 是否readonly
     */
    protected $readonly = false;

    /**
     * @var string
     */
    protected $placeholder = '';

    /**
     * @var string fieldname
     */
    protected $name = '';

    /**
     * @var null
     */
    protected $form = null;

    /**
     * 占据一行的半分比
     * @var float
     */
    protected $col = 1.0;

    /**
     * @var array 错误信息
     */
    protected $errors = array();

    /**
     * @var null 默认值
     */
    protected $default = null;

    /**
     * @var null 固定值
     */
    protected $fixed = null;

    /**
     * @var array 验证规则
     */
    protected $rules = array();

    /**
     * @var array 验证不通过的提示
     */
    public $errorMessage = array(
        'mobile' => '请输入一个合法的手机号码' ,
    );

    /**
     * @var array 验证函数
     */
    protected $validateFuncArray = array();

    /**
     * @var null Validator类验证后得到的messagebag
     */
    protected $message = null;

    /**
     * @var null field value
     */
    protected $value = null;

    /**
     * @var string label
     */
    protected $label = '';

    /**
     * @var callable 绑定存储数据的闭包
     */
    protected $bindFunc = null;

    /**
     * @var array
     */
    protected $form_group = array(
        'class' => 'form-group' ,
        'style' => '' ,
    );

    /**
     * @param $name
     */
    public function __construct( $name ){
        $this->name = $name;
        $this->bindFunc = function( $item , $form , $field ){
            $name = $field->name();
            $item->$name = $this->form->value( $name );
            return $item;
        };
    }

    /**
     * @param $fieldname
     * @return static
     */
    public static function create( $fieldname ){
        return new static( $fieldname );
    }

    /**
     * $field->setRow(1)  占据一整行
     * $field->setRow(0.5) 占据半行
     * @param $col
     * @return $this
     */
    public function setCol( $col ){
        $this->col = $col;
        return $this;
    }

    public function colClass(){
        $col = ceil( $this->col * 12.0 );
        return "col-md-{$col} col-lg-{$col}";
    }


    /**
     * @param $fieldname
     * @param $type
     * @return static
     */
    final public static function createByType( $fieldname , $type ){
        switch( $type ){
            case static::Type_Text:
                return Text::create( $fieldname );
            case static::Type_Password:
                return Password::create( $fieldname );
            case static::Type_TextArea:
                return TextArea::create( $fieldname );
            case static::Type_CheckGroup:
                return CheckGroup::create( $fieldname );
            case static::Type_RadioGroup:
                return RadioGroup::create( $fieldname );
            case static::Type_Select:
                return Select::create( $fieldname );
            case static::Type_MultiSelect:
                return MultiSelect::create( $fieldname );
            case static::Type_Image:
                return Image::create( $fieldname );
            case static::Type_MultiImage:
                return MultiImage::create( $fieldname );
            case static::Type_Tags:
                return Tags::create( $fieldname );
            case static::Type_Article:
                return Article::create( $fieldname );
            case static::Type_Date:
                return Date::create( $fieldname );
            case static::Type_SelectItem:
                return SelectItem::create( $fieldname );
            case static::Type_DateTime:
                return DateTime::create( $fieldname );
            case static::Type_DateRange:
                return DateRange::create( $fieldname );
            case static::Type_Appendable:
                return Appendable::create( $fieldname );
        }
    }

    /**
     * @param KForm $form
     * @return $this
     */
    public function belongsToForm( KForm $form ){
        $this->form = $form;
        return $this;
    }

    /**
     * @return string
     */
    public function name(){
        return $this->name;
    }

    /**
     * @param $label
     * @return $this
     */
    public function setLabel( $label ){
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function label(){
        $label = $this->label;
        $explain = array(); //exclamation-sign
        foreach( $this->rules as $rule ){
            if( ! is_string( $rule ) ){
                continue;
            }
            if( 'email' === $rule ){
                $label.= static::tooltip( '一个合法的邮箱' , 'envelope' );
            }
            elseif( 'required' === $rule ){
                $label.= static::tooltip( '必填' , 'asterisk' );
            }
            elseif( 'mobile' === $rule ){
                $label.= static::tooltip( '手机' , 'phone' );
            }
            elseif( 'numeric' === $rule ){
                $explain[] = '数字';
            }
            elseif( 'integer' === $rule ){
                $explain[] = '整数';
            }
            elseif( preg_match( '/^max\:(.*)$/i' , $rule , $matches ) ){
                $explain[] = '小于' . $matches[1];
            }
            elseif( preg_match( '/^min\:(.*)$/i' , $rule , $matches ) ){
                $explain[] = '大于' . $matches[1];
            }
        }
        if( ! empty( $explain ) ){
            $label.= static::tooltip( implode( ',' , $explain ) , 'exclamation-sign' );
        }
        return $label;
    }

    /**
     * @param $string
     * @return $this
     */
    public function setPlaceholder( $string ){
        $this->placeholder = $string;
        return $this;
    }

    /**
     * @return string
     */
    public function placeholder(){
        if( ! $this->placeholder ){
            return $this->label;
        }
        else{
            return $this->placeholder;
        }
    }

    /**
     * @param $class
     * @return $this
     */
    public function addFormgroupClass( $class ){
        $this->form_group['class'].= ' ' . $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function formgroup(){
        $hidden = $this->isHide()?'display:none;':'';
        $hasError = $this->hasError()?'has-error':'';
        $options = $this->form_group;
        $options['class'].= ' '.$hasError;
        $options['class'].= $this->colClass();
        $options['style'].= $hidden;
        return HTML::attributes( $options );
    }

    /**
     * @param bool $hide
     * @return $this
     */
    public function hide( $hide = true ){
        $this->hide = (boolean) $hide;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHide(){
        return ( true == $this->hide );
    }

    /**
     * @param bool $readonly
     * @return $this
     */
    public function readonly( $readonly = true ){
        $this->readonly = (boolean) $readonly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReadonly(){
        return ( true == $this->readonly );
    }

    /**
     * 设置数据绑定函数
     * @param $func callable function( $item , $form , $field ){}
     * @return $this
     */
    public function setSaveFunc( $func ){
        if( is_callable( $func ) ){
            $this->bindFunc = $func;
        }
        else{
            $this->bindFunc = null;
        }
        return $this;
    }

    /**
     * 将form数据保存到item
     * @param      $item
     * @param null $form
     * @return mixed
     */
    public function valueToModel( $item , $form = null ){
        $func = $this->bindFunc;
        return $func( $item , $form , $this );
    }

    /**
     * 设置验证过滤规则
     * @param $rules array|string
     * @return $this
     */
    public function setRules( $rules ){
        if( ! is_array( $rules ) ){
            $rules = explode( '|' , $rules );
        }
        $this->rules = array();
        foreach( $rules as $rule ){
            if( strtolower( $rule ) == 'trim' ){
                $this->validateFuncArray[] = function(){
                    $this->value = trim( $this->value() );
                    return true;
                };
                continue;
            }
            if( strtolower( $rule ) == 'xss' ){
                $this->validateFuncArray[] = function(){
                    $this->value = e( $this->value() );
                    return true;
                };
                continue;
            }
            $this->rules[] = $rule;
        }
        return $this;
    }

    /**
     * @param string $pattern 需要匹配的正则表达式
     * @param string $msg 匹配不通过的错误提示
     * @return $this
     */
    public function setRegxRule( $pattern , $msg = '输入不符合规则'){
        $this->rules[] = 'regex:' . $pattern;
        $this->errorMessage['regex'] = $msg;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setDefault( $value ){
        $this->default = $value;
        return $this;
    }

    /**
     * @param $fixed
     * @return $this
     */
    public function setFixed( $fixed ){
        $this->fixed = $fixed;
        return $this;
    }

    /**
     * @param $value
     * @return bool
     */
    public function validate( $value ){
        $this->value = $value;
        foreach( $this->validateFuncArray as $func ){
            $this->value = $func();
        }
        if( null == $value ){
            $input = [];
        }
        else{
            $input = [ $this->name => $this->value ];
        }
        $validator = \Validator::make(
            $input ,
            [ $this->name => $this->rules ] ,
            $this->errorMessage
        );
        if( $validator->fails() ){
            $this->message = $validator->messages();
            $this->errors = $this->message->get( $this->name );
            return false;
        }
        else{
            $this->message = null;
            $this->error = null;
            return true;
        }
    }

    /**
     * 返回表单域的值
     * @return null
     */
    public function value(){
        if( null !== $this->fixed ){
            return $this->fixed;
        }
        if( null == $this->value && null != $this->default ){
            return $this->default;
        }
        return $this->value;
    }

    /**
     * @return bool
     */
    public function hasError(){
        return ! empty( $this->errors );
    }

    /**
     * 以字符串的形式返回所有错误
     * @param string $glue
     * @return string
     */
    public function errors( $glue=';' ){
        return implode( $glue , $this->errors );
    }

    /**
     * push一条错误信息
     * @param $error string
     * @return $this
     */
    public function pushError( $error ){
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return string
     */
    public function render(){
        $class_name = strtolower( get_class( $this ) );
        $class_name = explode( "\\" , $class_name );
        $class_name = array_pop( $class_name );
        return \Illuminate\Support\Facades\View::make( 'laravel-cms::formfields/' . $class_name )->with( 'field' , $this );
    }

    /**
     * 基于bootstrap3显示tooltip
     * @param $message
     * @param $icon
     * @return string
     */
    static function tooltip( $message , $icon ){
        $message = str_replace( "\"" , "&quot;" , $message );
        $html = <<<HTML
<span class="glyphicon glyphicon-{$icon}" style="margin-left: 8px " data-toggle="tooltip" data-placement="top" title="{$message}"></span>
HTML;
        return $html;
    }


} 