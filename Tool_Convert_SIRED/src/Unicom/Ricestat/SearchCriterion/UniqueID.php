<?php

namespace Unicom\Ricestat\SearchCriterion;

class UniqueID extends SearchCriterion {
    
    protected $Id;
    
    protected $tipo;
    
    const GRUPPO = 'Gruppo';
    const OSPITESINGOLO = 'OspiteSingolo';
    
    public function __construct( $Id, $tipo ) {
       if ( ! preg_match( '/^\d+$/', trim( $Id ) ) ) {
            throw New \InvalidArgumentException( "Invalid UniqueID Id" );
        }
        if ( ! in_array( $tipo, array( 'Gruppo', 'OspiteSingolo' ) ) ) {
            throw New \InvalidArgumentException( "Invalid UniqueID tipo" );
        }
        $this->Id = (int) $Id;
        $this->tipo = $tipo;
    }
    
    public function asXML() {
        return '<UniqueID Id="' . $this->Id . '" tipo="' . $this->tipo . '" />';
    }
    
}

