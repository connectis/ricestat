<?php

namespace Unicom\Ricestat;

class Italia extends Location {
    
    /**
     * 
     * @param integer $CodiceComune
     * @param integer $Provincia
     * @param integer $Stato
     */
    public function __construct( $CodiceComune, $Provincia, $Stato ) {
        $this->CodiceComune = $CodiceComune;
        $this->Provincia = $Provincia;
        $this->Stato = $Stato;
    }
    
    public function asXML() {
        return '<Italia CodiceComune="' . $this->CodiceComune . '" Provincia="' . $this->Provincia . '" Stato="' . $this->Stato . '" />';
    }
}


