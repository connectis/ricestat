<?php

namespace Unicom\Ricestat;

class SchedineRS {
    
    protected $xml_obj;
    
    public function __construct( $xml ) {
        $xml = str_replace( ' xsi:', ' ', $xml );
        $obj = null;
        try {
            $obj = simplexml_load_string( $xml );
        } catch ( \Exception $ex ) {
            
        }
        if ( $obj ) {
            $this->xml_obj = $obj;
        }
    }
    
    public function failed( ) {
        return isset( $this->xml_obj->Errors );
    }
    
    public function getError( ) {
        $error = array();
        if ( $this->failed() ) {
            $attributes = $this->xml_obj->Errors->Error->attributes();
            foreach ( $attributes as $name => $value ) {
                $error[ "$name" ] = "$value";
            }
        }
        return $error;
    }
    
}



