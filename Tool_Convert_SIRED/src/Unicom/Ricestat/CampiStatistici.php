<?php

namespace Unicom\Ricestat;

class CampiStatistici {
    
    /**
     *
     * @var string 
     */
    protected $tipoTurismo;
    
    /**
     *
     * @var string 
     */
    protected $mezzoDiTrasporto;
    
    /**
     *
     * @var string 
     */
    protected $tipoPrenotazione;
    
    /**
     * 
     * @param string $tipoTurismo
     * @param string $mezzoDiTrasporto
     * @param string $tipoPrenotazione
     * @throws \InvalidArgumentException
     */
    public function __construct( $tipoTurismo, $mezzoDiTrasporto, $tipoPrenotazione ) {        
        $this->tipoTurismo = $tipoTurismo;
        $this->mezzoDiTrasporto = $mezzoDiTrasporto;
        $this->tipoPrenotazione = $tipoPrenotazione;
    }
    
    /**
     * 
     * @return string
     */
    public function getTipoTurismo( ) {
        return $this->tipoTurismo;
    }
    
    /**
     * 
     * @return string
     */
    public function getMezzoDiTrasporto( ) {
        return $this->mezzoDiTrasporto;
    }
    
    /**
     * 
     * @return string
     */
    public function getTipoPrenotazione( ) {
        return $this->tipoPrenotazione;
    }
    
    public function asXML( ) {
        return '<CampiStatistici><TipoTurismo>' . $this->tipoTurismo . '</TipoTurismo><MezzoDiTrasporto>' . $this->mezzoDiTrasporto . '</MezzoDiTrasporto><TipoPrenotazione>' . $this->tipoPrenotazione . '</TipoPrenotazione></CampiStatistici>';
    }
    
}


