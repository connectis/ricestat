<?php

namespace Unicom\Ricestat;

class Estero extends Location {
    
    /**
     * 
     * @param integer $Stato
     */
    public function __construct( $Stato ) {
        $this->Stato = $Stato;
    }
    
    public function asXML() {
        return '<Estero Stato="' . $this->Stato . '" />';
    }
    
}


