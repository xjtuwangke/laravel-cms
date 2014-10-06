<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-9-1
 * Time: 4:11
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

trait ControllerEventTrait {

    protected $controllerAbort = null;

    public function setAbort( $abort = null ){
        $this->controllerAbort = $abort;
    }

    public function abort(){
        return $this->controllerAbort;
    }

    public function fireControllerEvent( $name , $parameters = array() ){
        array_unshift( $parameters , $this );
        $results = Event::fire( $name , $parameters );
        return $results;
    }

} 