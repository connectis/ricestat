<?php

namespace Unicom\Ricestat;

class ImpostaDiSoggiorno {
    
    /**
     *
     * @var string 
     */
    protected $codiceImpostaSoggiorno;
    
    /**
     *
     * @var string 
     */
    protected $valoreImpostaUnitaria;
    
    /**
     *
     * @var string 
     */
    protected $nottiImponibili;
    
    /**
     * 
     * @param string $codiceImpostaSoggiorno
     * @param string $valoreImpostaUnitaria
     * @param string $nottiImponibili
     */
    public function __construct( $codiceImpostaSoggiorno, $valoreImpostaUnitaria, $nottiImponibili ) {
        $this->codiceImpostaSoggiorno = $codiceImpostaSoggiorno;
        $this->valoreImpostaUnitaria = $valoreImpostaUnitaria;
        $this->nottiImponibili = $nottiImponibili;
    }
    
    /**
     * 
     * @return string
     */
    public function getCodiceImpostaSoggiorno( ) {
        return $this->codiceImpostaSoggiorno;
    }
    
    /**
     * 
     * @return string
     */
    public function getValoreImpostaUnitaria( ) {
        return $this->valoreImpostaUnitaria;
    }
    
    /**
     * 
     * @return string
     */
    public function getNottiImponibili( ) {
        return $this->nottiImponibili;
    }
    
    public function asXML( ) {
        return '<ImpostaDiSoggiorno><CodiceImpostaSoggiorno>' . $this->codiceImpostaSoggiorno . '</CodiceImpostaSoggiorno><ValoreImpostaUnitaria>' . $this->valoreImpostaUnitaria . '</ValoreImpostaUnitaria><NottiImponibili>' . $this->nottiImponibili . '</NottiImponibili></ImpostaDiSoggiorno>';
    }
    
}


