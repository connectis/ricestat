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

// generate xml content
$Ids = array( '231140', '331144', '331150', '331154', '331157', '331159', '231144', '3311350' );
$xml =  $rq->eliminazione( $Ids );
file_put_contents( 'xml/eliminazione.xml', $xml ); // log data

// init client and send data to service
$rest_cli = new REST\Client();
$rest_cli->setLogFile( __DIR__ . '/logs/eliminazione.txt' );
$resp = $rest_cli->send( $xml );
print_r( $resp->getError() );
