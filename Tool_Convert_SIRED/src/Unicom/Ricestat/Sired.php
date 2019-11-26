<?php

namespace Unicom\Ricestat;

class Sired extends SchedinaQuestura {
    
    /**
     *
     * @var string 
     */
    protected $parsing_rules = 'Tipo Alloggiato;2|Data Arrivo;10|Cognome;50|Nome;30|Sesso;1|Data Nascita;10|Comune Nascita;9|Provincia di nascita;2|Stato Nascita;9|Cittadinanza;9|Codice comune di residenza;9|Provincia di residenza;2|Codice stato di residenza;9|Indirizzo;50|Codice tipo documento di identita;5|Numero documento di identita;20|Luogo o Stato rilascio documento;9|Data Partenza;10|Tipo Turismo;30|Mezzo di Trasporto;30|Camere occupate;3|Camere disponibili;3|Letti disponibili;4|Tassa soggiorno;1|Codice identificativo posizione;10|Modalita;1';
    
    protected function getExtraHeaders( ) {
        return array( );
    }
    
    protected function extraParsing( $item ) {
        return $item;
    }
    
    protected function parseDataPartenza( $data ) {
        return $this->parseDate( $data );
    }
    
    public static function to_sired( $file, $format = 'txt' ) {
        $dest = dirname( $file ) . '/SIRED-' . basename( $file );
        $sd = new SchedinaQuestura( $file );
        $sd2 = new self( $file );
        $specs = array_map( function( $item ) {
            $p =  explode( ';', $item );
            return array( 'header' => $p[0], 'length' => $p[1] );
        }, preg_split( '/\|/', $sd2->parsing_rules ) );
        
        $headers = array_column( $specs, 'header' );
        $lengths = array_column( $specs, 'length' );
        
        if ( $format == 'csv' ) {
            $field_sep = ';';
            $li = 0;
            file_put_contents( $dest, implode( $field_sep, $headers ) );
        } else {
            $field_sep = '';
            $li = 1;
            if ( is_readable( $dest ) ) {
                unlink( $dest );
            }
        }
        
        $line_sep  = "\r\n";
        
        $lines_cnt = 0;
        foreach ( $sd as $item ) {
            if ( empty( $item['Tipo Alloggiato'] ) ) {
                continue;
            }
            
            $line = '';
            if ( $lines_cnt >= $li ) {
                $line .= $line_sep;
            }
            
            for ( $i=0; $i < count( $headers ); $i++ ) {
                if ( $i > 0 ) {
                    $line .= $field_sep;
                }
                if ( array_key_exists( $headers[ $i ], $item ) ) {
                    $line .= str_pad( $item[ $headers[ $i ] ], $lengths[ $i ], ' ' );
                } else {
                    $line .= str_pad( '', $lengths[ $i ], ' ' );
                }
            }
            
            file_put_contents( $dest, $line, FILE_APPEND );
            $lines_cnt++;
            
            print_r( $item );
        }
    }
    
    public static function parse_data_file( $file ) {
        $operation_items = array( 'inserimentoAlloggiati' => array(), 'aggiornamento' => array(), 'eliminazione' => array() );
        
        $ids_file = __DIR__ . '/IDs.txt';
        if ( is_readable( $ids_file ) ) {
            unlink( $ids_file );
        }
        
        $groups         = array();
        $IdGruppo       = null;
        $group          = null;
        $rooms          = 0;
        $idCamera_base  = '';
        $assigned_rid   = 0;
        
        $all_items = array();
        $sq       = new self( $file );

        foreach ( $sq as $item ) {
            if ( empty( $item['Tipo Alloggiato'] ) ) {
                continue;
            }
            
            //print_r( $item );
            $all_items[] = $item;
            
//			$item['Codice identificativo posizione']="A".$item['Codice identificativo posizione'] ;
            $operation = 1 == $item['Modalita'] ? 'insert' : ( 2 == $item['Modalita'] ? 'update' : 'delete' );
            file_put_contents( __DIR__ . '/IDs.txt', "\n" . $item['Codice identificativo posizione']. "|" . $operation, FILE_APPEND );
            
            if ( 3 == $item['Modalita'] ) { // delete
                $operation_items['eliminazione'][] = $item['Codice identificativo posizione'];
            } 
            elseif ( 2 == $item['Modalita'] ) { // update
                //print_r( $item );
                $Id         = $item['Codice identificativo posizione'];
                $item['Id'] = $Id;
                $IdCamera   = preg_replace( '/\-/', '', $item['Data Arrivo'] ) . '_' . $Id;
                $ospite     = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] ); // Id, IdCamera, TipoAlloggiato
                $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                       ->setDataDiPartenza( $item['Data Partenza'] )
                       ->setSesso( $item['Sesso'] )
                       ->setDataDiNascita( $item['Data Nascita'] )
                       ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );




                    // Estero  Codice comune di residenza;9|Provincia di residenza;2|Codice stato di residenza;
                   	if($item['Codice stato di residenza']<>''){
						  $Stato = $item['Codice stato di residenza'];
					  }else{
						  $Stato = $item['Stato Nascita'];
					  } 
					  
					  if ( '' == trim( $item['Codice comune di residenza'] ) ) { 

                        $ospite->setProvenienza( new Estero($Stato ) );
                    } else { // Italia

                      if($item['Codice comune di residenza']<>''){
						  $CodiceComune = $item['Codice comune di residenza'];
					  }else{
						  $CodiceComune = $item['Comune Nascita'];
					  }

                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $ospite->setProvenienza( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }

                    // Estero  Codice comune di residenza;9|Provincia di residenza;2|Codice stato di residenza;
                    if ( '' == trim( $item['Comune Nascita'] ) ) { 
                        $ospite->setNascita( new Estero( $item['Stato Nascita'] ) );
                    } else { // Italia
                        $CodiceComune = $item['Comune Nascita'];
                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $Stato        = $item['Stato Nascita'];
                        $ospite->setNascita( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }


                
                $operation_items['aggiornamento'][] = $ospite;
            } 
            elseif ( 1 == $item['Modalita'] ) { // create
                //print_r( $item );
                
                /**
                 * according to the xml schema, InserimentoAlloggiati has either:
                 * two or more Gruppo
                 *      a Gruppo requires at least 2 Ospite:
                 *          the first one should have its TipoAlloggiato attribute set to 17 or 18
                 *          and the subsequent should have their TipoAlloggiato attributes set to 19 or 20
                 *      note: any item with TipoAlloggiato set to 16 should not be in a Gruppo
                 * OR 
                 * a only one OspiteSingolo
                 * 
                 */
                
                // building a Gruppo


			if( in_array( $item['Tipo Alloggiato'], array(  16) )  ){
				        // maybe save last group
				if ( $group && count( $group ) >= 2 ) {
					$operation_items['inserimentoAlloggiati'][] = $group;
				}
				$group=null;
			}

			if( in_array( $item['Tipo Alloggiato'], array(  17, 18 ) )  || !is_null($group)){

                if ( in_array( $item['Tipo Alloggiato'], array( 17, 18, 19, 20 ) ) ) {
                    // new Gruppo
                    if ( in_array( $item['Tipo Alloggiato'], array( 17, 18 ) ) ) {
                        // save existing Gruppo
                        if ( $group && count( $group ) > 0 ) {
                            $operation_items['inserimentoAlloggiati'][] = $group;
                        }
                        $IdGruppo = $item['Codice identificativo posizione'];
                        $group    = new Gruppo( $IdGruppo );
                        $rooms    = (int) $item['Camere occupate'];
                        $idCamera_base = preg_replace( '/\-/', '', $item['Data Arrivo'] ) . '_' . $IdGruppo;
                        $assigned_rid = 0;
                    
                    }
 
                    // building Ospite instance
                    $Id         = $item['Codice identificativo posizione'];
                    $item['Id'] = $Id;
 //      echo      $Id ;        
                    // generate IdCamera
                    if(is_null($group)){
						echo "1( $Id, $IdCamera, ".$item['Tipo Alloggiato']." )";
					die();
					} 
					if ( 1 == $rooms ) {
                        $IdCamera = $idCamera_base; 
                    } else {
                        $assigned_rid++;
                        if ( $assigned_rid > $rooms ) {
                            $assigned_rid = 1;
                        }
                        $IdCamera = $idCamera_base . '_' . $assigned_rid;
                    }
                    if(is_null($group)){
						echo "2( $Id, $IdCamera, ".$item['Tipo Alloggiato']." )";
					die();
					}
                    $ospite = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] ); // Id, IdCamera, TipoAlloggiato

                    $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                           ->setDataDiPartenza( $item['Data Partenza'] )
                           ->setSesso( $item['Sesso'] )
                           ->setDataDiNascita( $item['Data Nascita'] )
                           ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );

                    // Estero  Codice comune di residenza;9|Provincia di residenza;2|Codice stato di residenza;
                   	if($item['Codice stato di residenza']<>''){
						  $Stato = $item['Codice stato di residenza'];
					  }else{
						  $Stato = $item['Stato Nascita'];
					  } 
					  
					  if ( '' == trim( $item['Codice comune di residenza'] ) ) { 

                        $ospite->setProvenienza( new Estero($Stato ) );
                    } else { // Italia

                      if($item['Codice comune di residenza']<>''){
						  $CodiceComune = $item['Codice comune di residenza'];
					  }else{
						  $CodiceComune = $item['Comune Nascita'];
					  }

                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $ospite->setProvenienza( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }

                    // Estero 
                    if ( '' == trim( $item['Comune Nascita'] ) ) { 
                        $ospite->setNascita( new Estero( $item['Stato Nascita'] ) );
                    } else { // Italia
                        $CodiceComune = $item['Comune Nascita'];
                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $Stato        = $item['Stato Nascita'];
                        $ospite->setNascita( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }



					if(is_null($group)){
						echo " qui2( $Id, $IdCamera, ".$item['Tipo Alloggiato']." )";
					//die();
					}
//print_r($ospite);
                    // add Ospite to Gruppo
                    $group->setOspite( $ospite );

                }
					} 
					elseif ( 16 == $item['Tipo Alloggiato'] ) { // building OspiteSingolo
	//				elseif ( 16 == $item['Tipo Alloggiato'] || is_null($group)) { // building OspiteSingolo
					$group=null;
					$item['Tipo Alloggiato']=16;
                    $IdGruppo   = $item['Codice identificativo posizione'];
                    $Id         = $item['Codice identificativo posizione'];
                    $IdCamera   = preg_replace( '/\-/', '', $item['Data Arrivo'] ) . '_' . $IdGruppo; 
                    $item['Id'] = $Id;
                    $singolo    = new OspiteSingolo( $IdGruppo );
                    $ospite     = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] );
                    $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                           ->setDataDiPartenza( $item['Data Partenza'] )
                           ->setSesso( $item['Sesso'] )
                           ->setDataDiNascita( $item['Data Nascita'] )
                           ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );


                    // Estero  Codice comune di residenza;9|Provincia di residenza;2|Codice stato di residenza;
                   	if($item['Codice stato di residenza']<>''){
						  $Stato = $item['Codice stato di residenza'];
					  }else{
						  $Stato = $item['Stato Nascita'];
					  } 
					  
					  if ( '' == trim( $item['Codice comune di residenza'] ) ) { 

                        $ospite->setProvenienza( new Estero($Stato ) );
                    } else { // Italia

                      if($item['Codice comune di residenza']<>''){
						  $CodiceComune = $item['Codice comune di residenza'];
					  }else{
						  $CodiceComune = $item['Comune Nascita'];
					  }

                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $ospite->setProvenienza( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }

                    // Estero  Codice comune di residenza;9|Provincia di residenza;2|Codice stato di residenza;
                    if ( '' == trim( $item['Comune Nascita'] ) ) { 
                        $ospite->setNascita( new Estero( $item['Stato Nascita'] ) );
                    } else { // Italia
                        $CodiceComune = $item['Comune Nascita'];
                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $Stato        = $item['Stato Nascita'];
                        $ospite->setNascita( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }


                    $singolo->setOspite( $ospite );
                    $operation_items['inserimentoAlloggiati'][] = $singolo;
                    
                }

            }else{
			echo "Attenzione ERRORE: Guardare LOG";

			} // end create

        }
        
        // maybe save last group
        if ( $group && count( $group ) >= 2 ) {
            $operation_items['inserimentoAlloggiati'][] = $group;
        }
        
        //file_put_contents( __DIR__ . '/data.php', '<?php ' . "\nreturn " . var_export( $all_items, true ). ";" );
        
        return $operation_items;
    }

    protected static function get_operation_data( SchedineRQ $rq, $file ) {
        $results = array();
        $data = self::parse_data_file( $file );
        foreach ( $data as $operation => $items ) {
            if ( empty( $items ) ) {
                continue;
            }
            switch ( $operation ) {
                case 'inserimentoAlloggiati':
                    $results[] = array( 'operation' => $operation, 'data' => $rq->inserimentoAlloggiati( $items ) );
                    break;
                case 'aggiornamento':
                    $results[] = array( 'operation' => $operation, 'data' => $rq->aggiornamento( $items ) );
                    break;
                case 'eliminazione':
                    $results[] = array( 'operation' => $operation, 'data' => $rq->eliminazione( $items ) );
                    break;
            }
        }
        
        //print_r( $results );
        //exit;
        return $results;
    }

}


