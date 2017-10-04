<?php

namespace Unicom\Ricestat;

abstract class Location {
    
    /**
     *
     * @var integer 
     */
    protected $CodiceComune;
    
    /**
     *
     * @var integer 
     */
    protected $Provincia;
    
    /**
     *
     * @var integer 
     */
    protected $Stato;
    
    /**
     * 
     * @return integer
     */
    public function getCodiceComune() {
        return $this->CodiceComune;
    }
    
    /**
     * 
     * @return integer
     */
    public function getProvincia() {
        return $this->Provincia;
    }
    
    /**
     * 
     * @return integer
     */
    public function getStato() {
        return $this->Stato;
    }
    
    abstract public function asXML();
    
}
