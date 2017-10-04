<?php

namespace Unicom\Ricestat\Service\REST;

use Unicom\Http\Transport\Curl as CurlTrans;

class Client {
    
    //protected $base_uri = 'https://test.motouristoffice.it/MTO_SchedinaRQ.php';
    protected $http_cli;
    
    protected $log_file;
    protected  $base_uri ;
    
    public function __construct($base_uri) {
        $this->http_cli = new CurlTrans( $base_uri );
    }

    public function send( $data ) {
        $this->log( "SENDING DATA TO SERVICE:\n$data", "\n\n" );
        
        $this->http_cli->setHeader( 'Content-Type', 'text/xml; charset="utf-8"' );
        $result = $this->http_cli->post( $data );
        
        $this->log( "\n" . $this->http_cli->getDebugInfo() );
        $this->log( "DATA RECEIVED: \n" . $result );
        
        return new \Unicom\Ricestat\SchedineRS( $result );
    }
    
    public function setLogFile( $log_file ) {
        $dir = dirname( $log_file );
        if ( ! is_dir( $dir ) ) {
            @mkdir( $dir, 0755, true );
        }
        if ( is_writable( $dir ) ) {
            $this->log_file = $log_file;
        }
    }
    
    protected function log( $data, $nl = "\n" ) {
        if ( $this->log_file ) {
            $log = sprintf( "{$nl}%s\t%s", date( 'r' ), $data );
            file_put_contents( $this->log_file, $log, FILE_APPEND );
        }
    }
}


