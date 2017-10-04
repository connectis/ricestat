<?php

namespace Unicom\Ricestat;


/**
 * @author
 */
class SchedineRQ {
    
    /**
     *
     * @var Source 
     */
    protected $src1;
    
    /**
     *
     * @var Source 
     */
    protected $src2;
    
    /**
     * 
     * @param \Unicom\Ricestat\Source $src1
     * @param \Unicom\Ricestat\Source $src2
     */
    public function __construct( Source $src1, Source $src2 ) {
        $this->src1 = $src1;
        $this->src2 = $src2;
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\Ospite|array $Ospite
     * @return string
     */
    public function aggiornamento( $items ) {
        $xml = '<Aggiornamento>';
        if ( is_array( $items ) ) {
            foreach ( $items as $Ospite ) {
                if ( $Ospite instanceof Ospite ) {
                    $xml .= $Ospite->asXML();
                }
            }
        } elseif ( $items instanceof Ospite ) {
            $xml .= $items->asXML();
        }
        $xml .= '</Aggiornamento>';
        return $this->wrap( $xml );
    }
    
    /**
     * 
     * @param string|array $Ids
     * @return string
     */
    public function eliminazione( $Ids ) {
        $xml = '<Eliminazione>';
        if ( is_array( $Ids ) ) {
            foreach ( $Ids as $Id ) {
                $xml .= '<Alloggiato Id="' . $Id . '" />';
            }
        } else {
            $xml .= '<Alloggiato Id="' . $Ids . '" />';
        }
        $xml .= '</Eliminazione>';
        return $this->wrap( $xml );
    }
    
    /**
     * 
     * @param array $groups
     * @return string
     */
    public function inserimentoAlloggiati( array $groups ) {
        $xml = '<InserimentoAlloggiati>';
        
        if ( count( $groups ) ) {
            foreach ( $groups as $group ) {
                if ( $group instanceof Gruppo ) {
                    $xml .= $group->asXML();
                }
            }
        }

        $xml .= '</InserimentoAlloggiati>';
        return $this->wrap( $xml );
    }
    
    /**
     * 
     * @param \Unicom\Ricestat\SearchCriterion\SearchCriterion $SearchCriterion
     * @return string
     */
    public function requestSegments( \Unicom\Ricestat\SearchCriterion\SearchCriterion $SearchCriterion ) {
        $xml = '<RequestSegments>';
        $xml .= '<RequestSegment>';
        $xml .= '<SearchCriteria>';
        $xml .= '<Criterion>';
        $xml .= $SearchCriterion->asXML();
        $xml .= '</Criterion>';
        $xml .= '</SearchCriteria>';
        $xml .= '</RequestSegment>';
        $xml .= '</RequestSegments>';
        return $this->wrap( $xml );
    }
    
    /**
     * 
     * @param string $data
     * @return string
     */
    public function wrap( $data ) {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<MTO_SchedineRQ xmlns:xs="http://www.w3.org/2001/XMLSchema" xsi:schemaLocation="http://ws.webci.it/webci.xsd" Version="1.0" PrimaryLangID="it">';
        $xml .= '<POS>';
        $xml .= $this->src1->asXML();
        $xml .= $this->src2->asXML();
        $xml .= '</POS>';
        $xml .= $data;
        $xml .= '</MTO_SchedineRQ>';
        return $xml;
    }
    
}

