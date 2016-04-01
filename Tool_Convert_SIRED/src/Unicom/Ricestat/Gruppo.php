<?php

namespace Unicom\Ricestat;

class Gruppo implements \Countable {

    protected $root_node = 'Gruppo';
    
    protected $id_str = 'IdGruppo';
    
    protected $IdGruppo;
    
    /**
     *
     * @var array 
     */
    protected $Ospite_items = array();
    
    /**
     * 
     * @param string $IdGruppo
     */
    public function __construct( $IdGruppo ) {
        $this->IdGruppo = $IdGruppo;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\Ospite $Ospite
     * @return \Unicom\Ricestat\Gruppo
     */
    public function setOspite( Ospite $Ospite ) {
        $this->Ospite_items[] = $Ospite;
        return $this;
    }
    
    public function asXML() {
        $xml = '<' . $this->root_node . ' ' . $this->id_str . '="' . $this->IdGruppo . '">';
        foreach ( $this->Ospite_items as $Ospite ) {
            $xml .= $Ospite->asXML();
        }
        $xml .= '</' . $this->root_node . '>';
        return $xml;
    }

    public function count( $mode = 'COUNT_NORMAL' ) {
        return count( $this->Ospite_items );
    }

}
