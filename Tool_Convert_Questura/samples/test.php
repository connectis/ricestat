<?php

namespace Unicom\Ricestat;

use Unicom\Ricestat\Service\REST;

// autoloader
include '../autoload.php';
\Unicom_Autoload::register();
\Unicom_Autoload::registerPath( __DIR__ . '/../src' );

$src1 = new Source( 4, 'webci', 'Prova7592' );
$src2 = new Source( 10, '052004ALB0013', 'Prova7592' );

$file = __DIR__ . '/052004ALB0013_questura_2013-08-19_1117.txt';       
try {
    
    // new request
    $rq         = new SchedineRQ( $src1, $src2 );
    
    // load items from file
    $sq         = new SchedinaQuestura( $file );
    
    // organize items in groups ( 2 per Ospite group: TipoAlloggiato(17, 18) and TipoAlloggiato(19, 20) or a OspiteSingolo with a single Ospite insid )
    $groups     = array();
    $IdGruppo   = rand( 100000, 999999 );
    $group      = new Gruppo( $IdGruppo );
    
    foreach ( $sq as $item ) {
        if ( empty( $item['Tipo Alloggiato'] ) ) {
            continue;
        }
        
        // Capofamiglia (17) o Capogruppo (18)
        // Familiare (19) o Membro di gruppo (20)
        if ( ( count( $group ) == 0 && in_array( $item['Tipo Alloggiato'], array( 17, 18 ) ) ) OR 
             ( count( $group ) == 1 && in_array( $item['Tipo Alloggiato'], array( 19, 20 ) ) ) ) {
            $Id = rand( 100000, 999999 );
            $IdCamera = rand( 100000, 999999 ); 
            $ospite = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] ); // Id, IdCamera, TipoAlloggiato
            $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                   ->setDataDiPartenza( $item['Data Arrivo'] )
                   ->setSesso( $item['Sesso'] )
                   ->setDataDiNascita( $item['Data Nascita'] )
                   ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );
            $group->setOspite( $ospite );
        } elseif ( in_array( $item['Tipo Alloggiato'], array( 16 ) ) ) {
            $IdGruppo   = rand( 100000, 999999 );
            $Id         = rand( 100000, 999999 );
            $IdCamera   = rand( 100000, 999999 ); 
            $singolo    = new OspiteSingolo( $IdGruppo );
            $ospite     = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] );
            $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                   ->setDataDiPartenza( $item['Data Arrivo'] )
                   ->setSesso( $item['Sesso'] )
                   ->setDataDiNascita( $item['Data Nascita'] )
                   ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );
            $singolo->setOspite( $ospite );
            $groups[] = $singolo;
        }        
        
        if ( count( $group ) == 2 ) {
            $groups[] = $group;
            
            // start a new group
            $IdGruppo = rand( 100000, 999999 );
            $group    = new Gruppo( $IdGruppo );
        }
    }
    
    // generate xml request
    $xml =  $rq->inserimentoAlloggiati( $groups );
    file_put_contents( 'xml/test.xml', $xml ); // log request
    
    // send request
    $rest_cli = new REST\Client();
    $rest_cli->setLogFile( __DIR__ . '/logs/test.txt' );
    $resp = $rest_cli->send( $xml );
    print_r( $resp->getError() );
    
} catch ( \InvalidArgumentException $ex ) {
    echo 'Error: ' . $ex->getMessage();
}

