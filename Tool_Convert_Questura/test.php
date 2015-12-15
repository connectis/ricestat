<?php

namespace Unicom\Ricestat;

use Unicom\Ricestat\SearchCriterion as SearchCriterion;
use Unicom\Ricestat\Service\REST;

include 'autoload.php';
\Unicom_Autoload::register();
\Unicom_Autoload::registerPath( __DIR__ . '/src' );

$src1 = new Source( 4, 'webci', 'Prova7592' );
$src2 = new Source( 10, '052004ALB0013', 'Prova7592' );

$rq = new SchedineRQ( $src1, $src2 );

/**
 * InserimentoAlloggiati
 */
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

$xml =  $rq->inserimentoAlloggiati( $groups );
file_put_contents( 'inserimentoAlloggiati.xml', $xml );
echo "inserimentoAlloggiati generated\n";

$rest_cli = new REST\Client();
$resp = $rest_cli->send( $xml );
print_r( $resp->getError() );

exit;

/**
 * aggiornamento
 */
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
$xml =  $rq->aggiornamento( $ospite );
//file_put_contents( 'aggiornamento.xml', $xml );
//echo "aggiornamento done\n";

/**
 * eliminazione
 */
$Ids = array( '231140', '331144', '331150', '331154', '331157', '331159', '231144', '3311350' );
$xml =  $rq->eliminazione( $Ids );
//file_put_contents( 'eliminazione.xml', $xml );
//echo "eliminazione done\n";

/**
 * requestSegments examples
 */
$criterion = new SearchCriterion\DateRange( '2013-03-20', '2015-03-20' );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_DateRange.xml', $xml );
echo "RequestSegments_DateRange generated\n";
$rest_cli = new REST\Client();
$resp = $rest_cli->send( $xml );
print_r( $resp->getError() );

/*$criterion = new SearchCriterion\UniqueID( 231140, SearchCriterion\UniqueID::GRUPPO );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_UniqueID.xml', $xml );
echo "RequestSegments_UniqueID done\n";

$criterion = new SearchCriterion\ImpostaSoggiorno( 409052015 );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ImpostaSoggiorno.xml', $xml );
echo "RequestSegments_ImpostaSoggiorno done\n";

$criterion = new SearchCriterion\ListaResidenze( SearchCriterion\ListaResidenze::COMUNI );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ListaResidenze_COMUNI.xml', $xml );
echo "RequestSegments_ListaResidenze_COMUNI done\n";

$criterion = new SearchCriterion\ListaResidenze( SearchCriterion\ListaResidenze::STATI );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ListaResidenze_STATI.xml', $xml );
echo "RequestSegments_ListaResidenze_STATI done\n";

$criterion = new SearchCriterion\ListaStatistiche( SearchCriterion\ListaStatistiche::MEZZODITRASPORTO );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ListaStatistiche_MEZZODITRASPORTO.xml', $xml );
echo "RequestSegments_ListaStatistiche_MEZZODITRASPORTO done\n";

$criterion = new SearchCriterion\ListaStatistiche( SearchCriterion\ListaStatistiche::TIPOPRENOTAZIONE );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ListaStatistiche_TIPOPRENOTAZIONE.xml', $xml );
echo "RequestSegments_ListaStatistiche_TIPOPRENOTAZIONE done\n";

$criterion = new SearchCriterion\ListaStatistiche( SearchCriterion\ListaStatistiche::TIPOTURISMO );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ListaStatistiche_TIPOTURISMO.xml', $xml );
echo "RequestSegments_ListaStatistiche_TIPOTURISMO done\n";

$criterion = new SearchCriterion\ListaStatistiche( SearchCriterion\ListaStatistiche::TIPOTURISMO );
$xml =  $rq->requestSegments( $criterion );
file_put_contents( 'RequestSegments_ListaStatistiche_TIPOTURISMO.xml', $xml );
echo "RequestSegments_ListaStatistiche_TIPOTURISMO done\n";*/

echo "\n";


