<?php

namespace Xjtuwangke\LaravelCms\Controllers;

use Illuminate\Support\Facades\View;

class BaseController extends Controller {

    use \Xjtuwangke\LaravelCms\Controller\Traits\ControllerEventTrait;

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

    static public function registerRoutes(){
        $class = get_called_class();
        $ref = new \ReflectionClass( $class );
        $methods = $ref->getMethods( \ReflectionMethod::IS_STATIC );
        foreach( $methods as $method ){
            if( preg_match( '/^_routes_.*/' , $method->name ) ){
                $name = $method->name;
                static::$name();
            }
        }
    }

}