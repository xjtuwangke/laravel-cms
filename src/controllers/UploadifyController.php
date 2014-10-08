<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14-8-6
 * Time: 2:24
 */

namespace Xjtuwangke\LaravelCms\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Xjtuwangke\Random\KRandom;
use Xjtuwangke\LaravelModels\Images\ImageModel;
use Illuminate\Support\Facades\File;
use Xjtuwangke\LaravelModels\AuthModel;
use Illuminate\Support\Facades\URL;
use ProfileModel;

class UploadifyController extends BaseController{

    protected $imagine = null;
    protected $image = null;

    public static function _routes_uploadify(){
        //Route::post( "uploadify/image" , [ 'before' => [ 'csrf' ] , 'as' => "uploadify.image" , 'uses' => "UploadifyController@image" ] );
        Route::post( "uploadify/image" , [ 'as' => "uploadify.image" , 'uses' => "Xjtuwangke\\LaravelCms\\Controllers\\UploadifyController@image" ] );
    }

    public function image(){
        //$_POST: filename type
        //$_FILES: Filedata-> name type tmp_name error size
        if( array_key_exists( 'type' , $_POST ) ){
            $type = ucfirst( $_POST['type'] );
        }
        else{
            $type = 'Default';
        }

        if( ! @ $_FILES['Filedata']['tmp_name'] ){
            die('错误的请求');
        }

        $size = Input::file('Filedata')->getSize();
        $mine = Input::file('Filedata')->getMimeType();

        $this->imagine = new \Imagine\Gd\Imagine();

        $this->image = $this->imagine->open( $path = Input::file('Filedata')->getRealPath() );

        $method = 'image' . $type;
        if( method_exists( $this, $method ) ){
            $result = $this->$method();
        }
        else{
            $result = $this->imageDefault();
        }
        return Response::json( [ 'file_url' => $result , 'type' => $type  ] );
    }

    protected function imageDefault(){
        $path = 'upload/' . date('Ym/d/');
        $filename = KRandom::getRandStr() . '.jpg';
        if( ! File::exists( storage_path( $path ) ) ){
            File::makeDirectory( storage_path( $path ) , 493 , true );
        }
        while( File::exists( storage_path( $path ) . $filename ) ){
            $filename = KRandom::getRandStr() . '.jpg';
        }
        $this->image->save( storage_path( $path ) . $filename );
        ImageModel::createUploadedImage( $path . $filename , KUrl::upload( $path . $filename ) );
        return URL::asset( $path . $filename );
    }

    protected function imageAvatar(){
        $path = 'upload/' . date('Ym/d/');
        $filename = KRandom::getRandStr() . '.jpg';
        if( ! File::exists( storage_path( $path ) ) ){
            File::makeDirectory( storage_path( $path ) , 493 , true );
        }
        while( File::exists( storage_path( $path ) . $filename ) ){
            $filename = KRandom::getRandStr() . '.jpg';
        }
        $this->image->resize( new \Imagine\Image\Box( 300 , 300 ) )
            ->save( storage_path( $path ) . $filename );
        ImageModel::createUploadedImage( $path . $filename , KUrl::upload( $path . $filename ) );
        $user = AuthModel::user();
        $url = URL::asset( $path . $filename );
        if( $user ){
            if( $user->profile ){
                $user->profile->avatar = $url;
                $user->profile->save();
            }
            else{
                ProfileModel::create( array(
                    'user_id' => $user->id ,
                    'avatar' => $url
                ));
            }
        }
        else{
        }
        return $url;
    }
} 