<?php
    
    $data = file_get_contents( 'php://input' );
    log_str( "received data: \n$data"  );
    
    if ( preg_match( '/<?xml/is', $data ) ) {
        $MTO_SchedineRQ = simplexml_load_string( $data );
        log_str( "simplexml_load_string: \n" . print_r( $MTO_SchedineRQ, true ) );
    }
    
    function log_str( $data ) {
        $log = sprintf( "\n%s\t%s\t%s", date( 'r' ), $_SERVER['REMOTE_ADDR'], $data );
        file_put_contents( 'log.txt', $log, FILE_APPEND );
    }
    
    die( "processed: $data" );
    
    