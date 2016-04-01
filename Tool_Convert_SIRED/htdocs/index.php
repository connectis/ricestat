<?php
    namespace Unicom\Ricestat;

    use Unicom\Ricestat\Service\REST;
    
    error_reporting( E_ALL );
    @ini_set( 'display_errors', 1 );
     $base_uri_ese = 'http://ws.unicom.uno/MTO_SchedinaRQ.php';
			 $base_uri_test = 'http://test.motouristoffice.it/MTO_SchedinaRQ.php';

    // autoloader
    include '../autoload.php';
    \Unicom_Autoload::register();
    \Unicom_Autoload::registerPath( __DIR__ . '/../src' );

    $notices = array();
    
    if ( ! empty( $_FILES['data'] ) && ! empty( $_FILES['data']['tmp_name'] ) && is_readable( $_FILES['data']['tmp_name'] ) ) {
        $file = $_FILES['data']['tmp_name'];
        $auth = array( 
                    array( 'type' => $_POST['source']['type'][0], 'id' => $_POST['source']['id'][0], 'password' => $_POST['source']['password'][0] ),
                    array( 'type' => $_POST['source']['type'][1], 'id' => $_POST['source']['id'][1], 'password' => $_POST['source']['password'][1] ),
                );
		if( $_POST['endpoint']=='Produzione'){
			$endpoint=$base_uri_ese;
		}
		else
		{
			$endpoint=$base_uri_test;
        }

        $log_dir =  __DIR__  . '/logs/';
        
        try {
            
            $processor_class = SchedinaQuestura::get_processor( $_POST['file_format']);
            if ( $processor_class ) {
                $results = call_user_func_array( array( $processor_class, 'process' ), array( $auth, $file, $log_dir, $endpoint ) );
                foreach ( $results as $result ) {
                    $msg = $result['msg'];
                    if ( preg_match( '/\[log_file=(.*?)\]/is', $msg, $m ) ) {
                       $msg = preg_replace( '/\[log_file=(.*?)\]/is', '<!----><a target="_blank" href="' . str_replace( __DIR__, './', $m[1] ) . '">see logs</a>', $msg ); 
                    }
                        
                    if ( 'success' === $result['type'] ) {
                        $notices[] = '<div class="alert alert-success" role="alert">' . $msg . '</div>';
                    } 
                    elseif ( 'error' === $result['type'] ) {
                        $notices[] = '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
                    }
                }
            } else {
                throw new \Exception( "A processor was not found for this file format." );
            }
            
        } catch ( \Exception $ex ) {
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
        <title>TXT to Unicom</title>
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
                    <h1>Invio dati ad Unicom da file di testo </h1>
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
                            <legend>Parametri di Autenticazione Gestionale</legend>
					     <div class="form-group">
                            <label >End Point</label>
                            <div class="controls">
                                <select name="endpoint" id="endpoint">
                                    <!-- -->
									<option value="Test">Area di Test&nbsp;&nbsp;&nbsp;</option>
                                    <option value="Produzione">Produzione</option>
                                </select>
                            </div>
                        </div>
							 </fieldset>
							 <fieldset>
                            <legend>Parametri di Autenticazione Gestionale</legend>
                            <div class="form-group">
                                <label for="type_1">Type <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[type][]" id="type_1" placeholder="Type" value="4" readonly>
                            </div>
                            <div class="form-group">
                                <label for="id_1">ID <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[id][]" id="id_1"  placeholder="inserire ID Gestionale">
                            </div>
                            <div class="form-group">
                                <label for="password_1">Message Password <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[password][]" id="password_1"  placeholder="Inserire Password Gestionale">
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Parametri di Autenticazione Struttura Ricettiva</legend>
                            <div class="form-group">
                                <label for="type_2">Type <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[type][]" id="type_2" placeholder="Type" value="10" readonly>
                            </div>
                            <div class="form-group">
                                <label for="id_2">ID <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[id][]" id="id_2"  placeholder="Inserire ID Struttura Ricettiva">
                            </div>
                            <div class="form-group">
                                <label for="password_2">Message Password <span class="required">*</span></label>
                                <input type="text" class="form-control" name="source[password][]" id="password_2"  placeholder="Inserire Password struttura ricettiva">
                            </div>
                        </fieldset>

                        <div class="form-group">
                            <label for="data_file">Data File <span class="required">*</span></label>
                            <input type="file" id="data_file" name="data" >
                        </div>
                        
                        <div class="form-group">
                            <label for="file_format">File Format <span class="required">*</span></label>
                            <div class="controls">
                                <select name="file_format" id="file_format">
                                    <!--<option value="">Default</option> -->
                                    <option value="sardinia_emilia">Formato Gies: Sardegna/Pistoia/Rimini</option>
                                </select>
                            </div>
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
                    
                    if ( 'txt' !== data_file.val().split( '.' ).pop().toLowerCase() ) {
                        frm.prepend( '<div class="alert alert-danger" role="alert">Incorrect file format! Please choose a TXT file</div>' );
                        return false;
                    }
                    
                } );
                
            } );
            
        </script>
        
    </body>
</html>
