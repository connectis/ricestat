<?php
    namespace Unicom\Ricestat;

    use Unicom\Ricestat\Service\REST;

    // autoloader
    include '../autoload.php';
    \Unicom_Autoload::register();
    \Unicom_Autoload::registerPath( __DIR__ . '/../src' );
    
    //error_reporting( E_ALL );

    $notices = array();
    
    if ( ! empty( $_FILES['data'] ) && ! empty( $_FILES['data']['tmp_name'] ) && is_readable( $_FILES['data']['tmp_name'] ) ) {
        $file = $_FILES['data']['tmp_name'];
        $src1 = new Source( $_POST['source']['type'][0], $_POST['source']['id'][0], $_POST['source']['password'][0] );
        $src2 = new Source( $_POST['source']['type'][1], $_POST['source']['id'][1], $_POST['source']['password'][1] );
        
        try {
            
            // new request
            $rq        = new SchedineRQ( $src1, $src2 );

            // load items from file
            $sq        = new SchedinaQuestura( $file );

            // organize items in groups ( 2 per Ospite group: TipoAlloggiato(17, 18) and TipoAlloggiato(19, 20) or a OspiteSingolo with a single Ospite insid )
            $groups   = array();
            $IdGruppo = rand( 100000, 999999 );
            $group    = new Gruppo( $IdGruppo );
            foreach ( $sq as $item ) {
                if ( empty( $item['Tipo Alloggiato'] ) ) {
                    continue;
                }
                
                //print_r( $item );
                
                if ( count( $group ) >= 2 && in_array( $item['Tipo Alloggiato'], array( 17, 18 ) ) ) {
                    // save current group
                    $groups[] = $group;

                    // start a new group
                    $IdGruppo = rand( 100000, 999999 );
                    $group    = new Gruppo( $IdGruppo );
                }
                
                // Capofamiglia (17) o Capogruppo (18)
                // Familiare (19) o Membro di gruppo (20)
                if ( ( count( $group ) == 0 && in_array( $item['Tipo Alloggiato'], array( 17, 18 ) ) ) OR 
                     ( count( $group ) >= 1 && in_array( $item['Tipo Alloggiato'], array( 19, 20 ) ) ) ) {
                    $Id = rand( 100000, 999999 );
                    $item['Id'] = $Id;
                    $IdCamera = rand( 100000, 999999 ); 
                    $ospite = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] ); // Id, IdCamera, TipoAlloggiato
                    $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                           ->setDataDiPartenza( $item['Data Arrivo'] )
                           ->setSesso( $item['Sesso'] )
                           ->setDataDiNascita( $item['Data Nascita'] )
                           ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );
                    
                    // Estero
                    if ( '' == trim( $item['Comune Nascita'] ) ) { 
                        $ospite->setProvenienza( new Estero( $item['Stato Nascita'] ) );
                        $ospite->setNascita( new Estero( $item['Stato Nascita'] ) );
                    } else { // Italia
                        $CodiceComune = $item['Comune Nascita'];
                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $Stato        = $item['Stato Nascita'];
                        $ospite->setProvenienza( new Italia( $CodiceComune, $Provincia, $Stato ) );
                        $ospite->setNascita( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }
                    
                    $group->setOspite( $ospite );
                } 
                elseif ( in_array( $item['Tipo Alloggiato'], array( 16 ) ) ) {
                    $IdGruppo   = rand( 100000, 999999 );
                    $Id         = rand( 100000, 999999 );
                    $IdCamera   = rand( 100000, 999999 ); 
                    $item['Id'] = $Id;
                    $singolo    = new OspiteSingolo( $IdGruppo );
                    $ospite     = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] );
                    $ospite->setDataDiArrivo( $item['Data Arrivo'] )
                           ->setDataDiPartenza( $item['Data Arrivo'] )
                           ->setSesso( $item['Sesso'] )
                           ->setDataDiNascita( $item['Data Nascita'] )
                           ->setCittadinanza( new Cittadinanza( $item['Cittadinanza'] ) );
                    
                    // Estero
                    if ( '' == trim( $item['Comune Nascita'] ) ) { 
                        $ospite->setProvenienza( new Estero( $item['Stato Nascita'] ) );
                        $ospite->setNascita( new Estero( $item['Stato Nascita'] ) );
                    } else { // Italia
                        $CodiceComune = $item['Comune Nascita'];
                        $Provincia    = substr( $CodiceComune, 0, -3 );
                        $Stato        = $item['Stato Nascita'];
                        $ospite->setProvenienza( new Italia( $CodiceComune, $Provincia, $Stato ) );
                        $ospite->setNascita( new Italia( $CodiceComune, $Provincia, $Stato ) );
                    }
                    
                    $singolo->setOspite( $ospite );
                    $groups[] = $singolo;
                }
                
            }
            
            // maybe save last group
            if ( count( $group ) >= 2 ) {
                $groups[] = $group;
            }

            
            // generate xml request
            $xml = $rq->inserimentoAlloggiati( $groups );
            //die( $xml );
            
            // send request
            $log_file =  'test-' . date( 'Y-m-d' ) . '.txt';
            $rest_cli = new REST\Client();
            $rest_cli->setLogFile( __DIR__ . '/' . $log_file );
            $resp = $rest_cli->send( $xml );
            
            if ( $resp->getError() ) {
                $notices[] = '<div class="alert alert-danger" role="alert">' . $resp->getError() . '</div>';
            } else {
                $notices[] = '<div class="alert alert-success" role="alert">Data successfully sent to service <a target="_blank" href="' . $log_file . '">see logs</a></div>';
            }
            
        } catch ( Exception $ex ) {
            $notices[] = '<div class="alert alert-danger" role="alert">' . $ex->getMessage() . '</div>';
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Web Service Data Sender</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css" />
        
        <style>
            .form-group span.required {
                color: red;
                vertical-align: middle;
            }
        </style>
    </head>
    <body>
        
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Web Service Data Sender</h1>
                </div>
            </div>
        </div>
        
        <div class="container">
            
            <div class="row">
                <div class="col-md-12">
                    
                    <?php 
                        if ( ! empty( $notices ) ) {
                            echo implode( "\n", $notices );
                        }
                    ?>
                    
                    <form name="wbs" action="" method="post" enctype="multipart/form-data">
                        <fieldset>
                            <legend>Authentication 1</legend>
                            <div class="form-group">
                                <label for="type_1">Type <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[type][]" id="type_1" placeholder="Type" value="4">
                            </div>
                            <div class="form-group">
                                <label for="id_1">ID <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[id][]" id="id_1" placeholder="ID" value="webci">
                            </div>
                            <div class="form-group">
                                <label for="password_1">Message Password <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[password][]" id="password_1" placeholder="Message Password" value="Prova7592">
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Authentication 2</legend>
                            <div class="form-group">
                                <label for="type_2">Type <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[type][]" id="type_2" placeholder="Type" value="10">
                            </div>
                            <div class="form-group">
                                <label for="id_2">ID <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[id][]" id="id_2" placeholder="ID" value="052004ALB0013">
                            </div>
                            <div class="form-group">
                                <label for="password_2">Message Password <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[password][]" id="password_2" placeholder="Message Password" value="Prova7592">
                            </div>
                        </fieldset>

                        <div class="form-group">
                            <label for="data_file">Data File <span class="required">*</span></label>
                            <input type="file" id="data_file" name="data" >
                        </div>

                        <br /><br />

                        <button type="submit" class="btn btn-default" name="bt-send">Send</button>
                  </form>
                    
                    
                </div>
            </div>
            
            
        </div>
        
        <br /><br /><br /><br /><br />
        
        <script src="assets/js/jquery-1.11.3.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        
        <script>
        
            $( document ).ready( function( ) {
                
                $( 'form[name="wbs"]' ).submit( function( ) {
                    var frm = $( this );
                    frm.find( '.alert' ).remove();
                    
                    var type_1 = $( '#type_1' );
                    var id_1 = $( '#id_1' );
                    var password_1 = $( '#password_1' );
                    var type_2 = $( '#type_2' );
                    var id_2 = $( '#id_2' );
                    var password_2 = $( '#password_2' );
                    var data_file = $( '#data_file' );
                    
                    if ( '' == $.trim( type_1.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please provide Type for Authentication 1</div>' );
                        return false;
                    }
                    if ( '' == $.trim( id_1.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please provide ID for Authentication 1</div>' );
                        return false;
                    }
                    if ( '' == $.trim( password_1.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please provide Message Password for Authentication 1</div>' );
                        return false;
                    }
                    
                    if ( '' == $.trim( type_2.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please provide Type for Authentication 2</div>' );
                        return false;
                    }
                    if ( '' == $.trim( id_2.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please provide ID for Authentication 2</div>' );
                        return false;
                    }
                    if ( '' == $.trim( password_2.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please provide Message Password for Authentication 2</div>' );
                        return false;
                    }
                    
                    if ( '' == $.trim( data_file.val() ) ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Please choose a file</div>' );
                        return false;
                    }
                    
                    if ( 'txt' !== data_file.val().split( '.' ).pop() ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Incorrect file format! Please choose a TXT file</div>' );
                        return false;
                    }
                    
                } );
                
            } );
            
        </script>
        
    </body>
</html>
