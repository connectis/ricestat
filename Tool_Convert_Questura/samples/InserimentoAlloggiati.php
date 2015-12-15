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
$groups = array();

$group  = new Gruppo( 155 );

$ospite = new Ospite( 231140, 1602, 17 ); // Id, IdCamera, TipoAlloggiato
$ospite->setDataDiArrivo( '2015-03-20' )
       ->setDataDiPartenza( '2015-03-21' )
       ->setProvenienza( new Italia( 409100005, 409100, 100000100 ) )
       ->setSesso( Sesso::FEMMINA )
       ->setDataDiNascita( '1996-01-01' )
       ->setNascita( new Estero( 100000100 ) )
       ->setCittadinanza( new Cittadinanza( 100000100 ) )
       ->setCampiStatistici( new CampiStatistici( TipoTurismo::ALTRO, MezzoDiTrasporto::ALTRO, TipoPrenotazione::ALTRO ) )
       ->setImpostaDiSoggiorno( new ImpostaDiSoggiorno( 'MN', '1,5', 2 ) );
$group->setOspite( $ospite );

$ospite = new Ospite( 231144, 1602, 19 ); // Id, IdCamera, TipoAlloggiato
$ospite->setDataDiArrivo( '2015-03-20' )
       ->setDataDiPartenza( '2015-03-21' )
       ->setProvenienza( new Italia( 409100005, 409100, 100000100 ) )
       ->setSesso( Sesso::MASCHIO )
       ->setDataDiNascita( '1986-03-01' )
       ->setNascita( new Estero( 100000100 ) )
       ->setCittadinanza( new Cittadinanza( 100000100 ) )
       ->setCampiStatistici( new CampiStatistici( TipoTurismo::ALTRO, MezzoDiTrasporto::ALTRO, TipoPrenotazione::ALTRO ) )
       ->setImpostaDiSoggiorno( new ImpostaDiSoggiorno( 'MN', '1,5', 2 ) );
$group->setOspite( $ospite );

$groups[] = $group;


$group  = new OspiteSingolo( 331159 );
$ospite = new Ospite( 331159, 1355, 16 ); // Id, IdCamera, TipoAlloggiato
$ospite->setDataDiArrivo( '2015-03-20' )
       ->setDataDiPartenza( '2015-03-21' )
       ->setProvenienza( new Italia( 401001201, 401001, 100000100 ) )
       ->setSesso( Sesso::FEMMINA )
       ->setDataDiNascita( '1968-01-01' )
       ->setNascita( new Estero( 100000100 ) )
       ->setCittadinanza( new Cittadinanza( 100000100 ) )
       ->setCampiStatistici( new CampiStatistici( TipoTurismo::ALTRO, MezzoDiTrasporto::ALTRO, TipoPrenotazione::ALTRO ) )
       ->setImpostaDiSoggiorno( new ImpostaDiSoggiorno( 'MN', '1,5', 2 ) );
$group->setOspite( $ospite );

$groups[] = $group;

// generate xml content
$xml =  $rq->inserimentoAlloggiati( $groups );
file_put_contents( 'xml/inserimentoAlloggiati.xml', $xml ); // log data

// init client and send data to service
$rest_cli = new REST\Client();
$rest_cli->setLogFile( __DIR__ . '/logs/inserimentoAlloggiati.txt' );
$resp = $rest_cli->send( $xml );
print_r( $resp->getError() );







