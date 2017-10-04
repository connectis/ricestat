<?php

namespace Unicom\Ricestat;

class OspiteSingolo extends Gruppo {
    
    protected $root_node = 'OspiteSingolo';
    
    protected $id_str = 'Id';
    
    public function __construct( $IdGruppo ) {
        parent::__construct( $IdGruppo );
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\Ospite $Ospite
     * @return \Unicom\Ricestat\Gruppo
     */
    public function setOspite( Ospite $Ospite ) {
        $this->Ospite_items = array();
        $this->Ospite_items[] = $Ospite;
        return $this;
    }
    
}
