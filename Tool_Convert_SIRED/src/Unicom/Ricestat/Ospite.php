<?php

namespace Unicom\Ricestat;

class Ospite {
    
    /**
     *
     * @var integer
     */
    protected $Id;
    
    /**
     *
     * @var integer 
     */
    protected $IdCamera;
    
    /**
     *
     * @var string 
     */
    protected $DataDiArrivo;
    
    /**
     *
     * @var string 
     */
    protected $DataDiPartenza;
    
    /**
     *
     * @var string 
     */
    protected $DataDiNascita;
    
    /**
     *
     * @var integer 
     */
    protected $Sesso;
    
    /**
     *
     * @var \Unicom\Ricestat\Location 
     */
    protected $Provenienza;
    
    /**
     *
     * @var \Unicom\Ricestat\Location 
     */
    protected $Nascita;
    
    /**
     *
     * @var \Unicom\Ricestat\Cittadinanza 
     */
    protected $Cittadinanza;
    
    /**
     *
     * @var \Unicom\Ricestat\ImpostaDiSoggiorno 
     */
    protected $ImpostaDiSoggiorno;
    
    /**
     *
     * @var \Unicom\Ricestat\CampiStatistici 
     */
    protected $CampiStatistici;
    
    /**
     *
     * @var integer 
     */
    protected $TipoAlloggiato;
    
    /**
     * 
     * @param string $id
     * @param string $id_camera
     */
    public function __construct( $Id, $IdCamera, $TipoAlloggiato = null ) {
        $this->Id = $Id;
        $this->IdCamera = $IdCamera;
        if ( ! is_null( $TipoAlloggiato ) ) {
            $this->setTipoAlloggiato( $TipoAlloggiato );
        }
    }
    
    /**
     * 
     * @param string $DataDiArrivo
     * @return \Unicom\Ricestat\Ospite
     */
    public function setDataDiArrivo( $DataDiArrivo ) {
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $DataDiArrivo ) ) ) {
            throw New \InvalidArgumentException( "Invalid date (DataDiArrivo): $DataDiArrivo" );
        }
        $this->DataDiArrivo = $DataDiArrivo;
        return $this;
    }
    
    /**
     * 
     * @param string $DataDiPartenza
     * @return \Unicom\Ricestat\Ospite
     */
    public function setDataDiPartenza( $DataDiPartenza ) {
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $DataDiPartenza ) ) ) {
            throw New \InvalidArgumentException( "Invalid date (DataDiPartenza): $DataDiPartenza" );
        }
        $this->DataDiPartenza = $DataDiPartenza;
        return $this;
    }
    
    /**
     * 
     * @param string $DataDiNascita
     * @return \Unicom\Ricestat\Ospite
     */
    public function setDataDiNascita( $DataDiNascita ) {
        if ( ! $DataDiNascita ) {
            $DataDiNascita = '0000-00-00';
        }
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $DataDiNascita ) ) ) {
            throw New \InvalidArgumentException( "Invalid date (DataDiNascita): $DataDiNascita" );
        }
        $this->DataDiNascita = $DataDiNascita;
        return $this;
    }
    
    /**
     * 
     * @param integer $Sesso
     * @return \Unicom\Ricestat\Ospite
     */
    public function setSesso( $Sesso ) {
        $this->Sesso = $Sesso;
        return $this;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\Location $Provenienza
     * @return \Unicom\Ricestat\Ospite
     */
    public function setProvenienza( Location $Provenienza ) {
        $this->Provenienza = $Provenienza;
        return $this;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\Location $Nascita
     * @return \Unicom\Ricestat\Ospite
     */
    public function setNascita( Location $Nascita ) {
        $this->Nascita = $Nascita;
        return $this;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\Cittadinanza $Cittadinanza
     * @return \Unicom\Ricestat\Ospite
     */
    public function setCittadinanza( Cittadinanza $Cittadinanza ) {
        $this->Cittadinanza = $Cittadinanza;
        return $this;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\ImpostaDiSoggiorno $ImpostaDiSoggiorno
     * @return \Unicom\Ricestat\Ospite
     */
    public function setImpostaDiSoggiorno( ImpostaDiSoggiorno $ImpostaDiSoggiorno ) {
        $this->ImpostaDiSoggiorno = $ImpostaDiSoggiorno;
        return $this;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\CampiStatistici $CampiStatistici
     * @return \Unicom\Ricestat\Ospite
     */
    public function setCampiStatistici( CampiStatistici $CampiStatistici ) {
        $this->CampiStatistici = $CampiStatistici;
        return $this;
    }
    
    /**
     * 
     * @param integer $TipoAlloggiato
     */
    public function setTipoAlloggiato( $TipoAlloggiato ) {
        /*if ( ! in_array( $TipoAlloggiato, array( 16, 19, 20 ) ) ) {
            throw New \InvalidArgumentException( "Invalid value for TipoAlloggiato: $TipoAlloggiato" );
        }*/
        $this->TipoAlloggiato = (int) $TipoAlloggiato;
    }
    
    public function asXML() {
        $xml = '<Ospite Id="' . $this->Id . '" IdCamera="' . $this->IdCamera . '"' . ( $this->TipoAlloggiato ? ' TipoAlloggiato="' . $this->TipoAlloggiato . '"' : '' ) . '>';
        $xml .= '<DataDiArrivo>' . $this->DataDiArrivo . '</DataDiArrivo>';
        $xml .= '<DataDiPartenza>' . $this->DataDiPartenza . '</DataDiPartenza>';
        $xml .= '<DataDiNascita>' . $this->DataDiNascita . '</DataDiNascita>';
        $xml .= '<Sesso>' . $this->Sesso . '</Sesso>';
        if ( $this->Provenienza ) {
            $xml .= '<Provenienza>' . $this->Provenienza->asXML() . '</Provenienza>';
        }

        if ( $this->Nascita ) {
            $xml .= '<Nascita>' . $this->Nascita->asXML() . '</Nascita>';
        }
        $xml .= $this->Cittadinanza->asXML();
        if ( $this->ImpostaDiSoggiorno ) {
            $xml .= $this->ImpostaDiSoggiorno->asXML();
        }
        if ( $this->CampiStatistici ) {
            $xml .= $this->CampiStatistici->asXML();
        }
        $xml .= '</Ospite>';
        return $xml;
    }
    
}


