<?php

namespace Unicom\Ricestat\SearchCriterion;

class ListaResidenze extends SearchCriterion {

    protected $tipo;
    
    const STATI = 'Stati';
    const COMUNI = 'Comuni';
    
    public function __construct( $tipo ) {
        if ( ! in_array( $tipo, array( 'Stati', 'Comuni' ) ) ) {
            throw New \InvalidArgumentException( "Invalid ListaResidenze tipo: $tipo" );
        }
        $this->tipo = $tipo;
    }
    
    public function asXML() {
        return '<ListaResidenze tipo="' . $this->tipo . '" />';
    }
    
}

