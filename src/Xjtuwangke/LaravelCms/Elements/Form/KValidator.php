<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/9/24
 * Time: 03:48
 */

namespace Xjtuwangke\LaravelCms\Elements\Form;

use \Sms\SmServiceBase;

class KValidator extends \Illuminate\Validation\Validator{

    public function validateMobile( $attribute , $value , $parameters ){
        return SmServiceBase::isMobile( $value );
    }
} 