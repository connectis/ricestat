<?php

namespace Unicom\Ricestat;

class Cittadinanza extends Location {
    
    /**
     * 
     * @param integer $Stato
     */
    public function __construct( $Stato ) {
        $this->Stato = $Stato;
    }

    public function asXML() {
        return '<Cittadinanza Stato="' . $this->Stato . '" />';
    }

}
