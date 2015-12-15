<?php

namespace Unicom\Ricestat;

use Unicom\Ricestat\Service\REST;
use Unicom\Ricestat\SearchCriterion;

// autoloader
include '../../../autoload.php';
\Unicom_Autoload::register();
\Unicom_Autoload::registerPath( __DIR__ . '/../../../src' );

// init request
$src1 = new Source( 4, 'webci', 'Prova7592' );
$src2 = new Source( 10, '052004ALB0013', 'Prova7592' );
$rq   = new SchedineRQ( $src1, $src2 );

// generate xml content
$criterion = new SearchCriterion\ListaStatistiche( SearchCriterion\ListaStatistiche::TIPOTURISMO );
$xml       =  $rq->requestSegments( $criterion );
file_put_contents( '../../xml/requestSegments_ListaStatistiche_TIPOTURISMO.xml', $xml ); // log data

// init client and send data to service
$rest_cli = new REST\Client();
$rest_cli->setLogFile( __DIR__ . '/../../logs/requestSegments_ListaStatistiche_TIPOTURISMO.txt' );
$resp = $rest_cli->send( $xml );
print_r( $resp->getError() );
