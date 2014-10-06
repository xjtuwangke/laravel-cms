<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-15
 * Time: 20:25
 */

namespace Xjtuwangke\LaravelCms\Elements;

use Illuminate\Support\Facades\Session;

class KMessager {

    const NOTICE = 1;
    const WARNING = 2;
    const SUCCESS = 3;
    const ERROR = 4;

    static protected $sess_name = 'custom_message_flashdata';

    static function get(){
        $messages = Session::get( static::$sess_name , serialize( [] ) );
        return unserialize( $messages );
    }

    static function push( $text , $type = null ){
        $messages = static::get();
        $messages[] = [ $text , $type ];
        Session::set( static::$sess_name , serialize( $messages ) );
    }

    static function clear(){
        Session::set( static::$sess_name , serialize( [] ) );
    }

    static function show(){
        $messages = static::get();
        $html = '';
        foreach( $messages as $message ){
            $html.= static::showOnePice( $message[0] , $message[1] );
        }
        static::clear();
        return $html;
    }

    static function showOnePice( $text , $type ){

        switch( $type ){
            case static::NOTICE:
                $class = 'alert-info';
                break;
            case static::WARNING:
                $class = 'alert-warning';
                break;
            case static::SUCCESS:
                $class = 'alert-success';
                break;
            case static::ERROR:
                $class = 'alert-danger';
                break;
            default:
                $class = 'alert-info';
                break;
        }
        $html = <<<HTML
<div class="alert {$class} alert-dismissible fade in" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
  {$text}
</div>
HTML;
        return $html;
    }
} 