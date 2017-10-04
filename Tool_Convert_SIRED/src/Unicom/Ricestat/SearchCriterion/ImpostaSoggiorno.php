<?php

namespace Unicom\Ricestat\SearchCriterion;

class ImpostaSoggiorno extends SearchCriterion {
    
    protected $Comune;
    
    public function __construct( $Comune ) {
       if ( ! preg_match( '/^\d+$/', trim( $Comune ) ) ) {
            throw New \InvalidArgumentException( "Invalid UniqueID Id" );
        }
        $this->Comune = (int) $Comune;
    }
    
    public function asXML() {
        return '<ImpostaSoggiorno Comune="' . $this->Comune . '" />';
    }
    
}

