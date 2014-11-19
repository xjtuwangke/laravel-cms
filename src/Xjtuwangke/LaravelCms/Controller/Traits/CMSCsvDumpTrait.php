<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/11/19
 * Time: 22:12
 */

namespace Xjtuwangke\LaravelCms\Controller\Traits;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

trait CMSCsvDumpTrait {
    /**
     * @param array $attributes  array( '中文描述' => 'key' );
     * @param       $title
     * @return mixed
     */
    public function export_csv( array $attributes , $title = 'csv-data-dump' , $data = array() , $fromCharSet = 'UTF-8' , $toCharSet = 'UTF-8' ){

        $title.= '-' . date( 'Ymd-His');
        $config = new ExporterConfig();
        $config->setFromCharset( $fromCharSet )
            ->setToCharset( $toCharSet );

        $headers = array(
            'Content-type' => "application/csv; filename=\"{$title}.csv\"" ,
            'Content-Disposition' => "attachement; filename=\"{$title}.csv\"" ,
            'Cache-Control' => "no-cache" ,
        );
        $response = \Response::stream(
            function() use( $config , $attributes , $data ){
                $exporter = new Exporter($config);
                $exporter->export('php://output', array(
                    array_keys( $attributes ) ,
                    $attributes ,
                    $data ,
                ));
            }
            , 200
            , $headers);
        return $response;
    }

    public function import_csv( $filePath , $fromCharSet = 'UTF-8' , $toCharSet = 'UTF-8' ){
        $config = new LexerConfig();
        $config
            ->setDelimiter("\t") // Customize delimiter. Default value is comma(,)
            ->setEnclosure("'")  // Customize enclosure. Default value is double quotation(")
            ->setEscape("\\")    // Customize escape character. Default value is backslash(\)
            ->setToCharset( $toCharSet ) // Customize target encoding. Default value is null, no converting.
            ->setFromCharset( $fromCharSet ) // Customize CSV file encoding. Default value is null.
        ;
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        $data = array();
        $interpreter->addObserver(function(array $row) use (&$data) {
            $data[] = $row;
        });
        $lexer->parse( $filePath , $interpreter );
        $attributes = array();
        $items = array();
        foreach( $data as $i => $row ){
            if( 1 == $i ){
                $attributes = $row;
            }
            if( $i > 1 ){
                $one = array();
                foreach( $attributes as $j => $attribute ){
                    if( array_key_exists( $j , $row ) ){
                        $one[$attribute] = $row[$j];
                    }
                }
                $items[] = $one;
            }
        }
        return $items;
    }
}