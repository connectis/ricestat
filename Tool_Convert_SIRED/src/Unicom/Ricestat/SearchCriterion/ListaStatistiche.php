<?php

namespace Unicom\Ricestat\SearchCriterion;

class ListaStatistiche extends SearchCriterion {

    protected $tipo;
    
    const TIPOTURISMO = 'TipoTurismo';
    const MEZZODITRASPORTO = 'MezzoDiTrasporto';
    const TIPOPRENOTAZIONE = 'TipoPrenotazione';
    
    public function __construct( $tipo ) {
        if ( ! in_array( $tipo, array( 'TipoTurismo', 'MezzoDiTrasporto', 'TipoPrenotazione' ) ) ) {
            throw New \InvalidArgumentException( "Invalid ListaStatistiche tipo: $tipo" );
        }
        $this->tipo = $tipo;
    }
    
    public function asXML() {
        return '<ListaStatistiche tipo="' . $this->tipo . '" />';
    }
    
}

