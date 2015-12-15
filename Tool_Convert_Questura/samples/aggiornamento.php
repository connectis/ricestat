<?php

namespace Unicom\Ricestat;

use Unicom\Ricestat\Service\REST;

// autoloader
include '../autoload.php';
\Unicom_Autoload::register();
\Unicom_Autoload::registerPath( __DIR__ . '/../src' );

// init request
$src1 = new Source( 4, 'webci', 'Prova7592' );
$src2 = new Source( 10, '052004ALB0013', 'Prova7592' );
$rq   = new SchedineRQ( $src1, $src2 );

// prepare request content
$ospite = new Ospite( 231140, 1602, 17 ); // Id, IdCamera, TipoAlloggiato
$ospite->setDataDiArrivo( '2015-11-20' )
       ->setDataDiPartenza( '2015-11-21' )
       ->setProvenienza( new Italia( '', '', 100000100 ) )
       ->setSesso( Sesso::FEMMINA )
       ->setDataDiNascita( '1996-01-01' )
       ->setNascita( new Estero( 100000100 ) )
       ->setCittadinanza( new Cittadinanza( 100000100 ) )
       ->setCampiStatistici( new CampiStatistici( TipoTurismo::ALTRO, MezzoDiTrasporto::ALTRO, TipoPrenotazione::ALTRO ) )
       ->setImpostaDiSoggiorno( new ImpostaDiSoggiorno( 'MN', '1.00', 2 ) );

// generate xml content
$xml =  $rq->aggiornamento( $ospite );
file_put_contents( 'xml/aggiornamento.xml', $xml ); // log data

// init client and send data to service
$rest_cli = new REST\Client();
$rest_cli->setLogFile( __DIR__ . '/logs/aggiornamento.txt' );
$resp = $rest_cli->send( $xml );
print_r( $resp->getError() );
