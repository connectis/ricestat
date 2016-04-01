<?php

namespace Unicom\Http\Transport;

class Curl extends Transport {
    
    public function __construct( $uri ) {
        parent::__construct( $uri );
        $this->setHeader( 'Expect', '' );
    }
    
    /**
     * 
     * @return resource
     */
    public function getClient() {
        if ( ! $this->client ) {
            $this->client = curl_init( $this->uri );
        }
        return parent::getClient();
    }
    
    public function request( $method, $data = null ) {
        $method = trim( strtoupper( $method ) );
        
        $ch = $this->getClient();
        
        if ( ! is_null( $this->autoreferer ) ) {
            curl_setopt( $ch, CURLOPT_AUTOREFERER, $this->autoreferer );
        }
        if ( ! is_null( $this->cookie_session ) ) {
            curl_setopt( $ch, CURLOPT_COOKIESESSION, $this->cookie_session );
        }
        if ( ! is_null( $this->auto_redirect ) ) {
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, $this->auto_redirect );
        }
        if ( ! is_null( $this->retrieve_headers ) ) {
            curl_setopt( $ch, CURLOPT_HEADER, $this->retrieve_headers );
        }
        if ( ! is_null( $this->enable_proxy_tunnel ) ) {
            curl_setopt( $ch, CURLOPT_HTTPPROXYTUNNEL, $this->enable_proxy_tunnel );
        }
        if ( 'POST' === $method ) {
            curl_setopt( $ch, CURLOPT_POST, true );
        } elseif ( in_array( $method, array( 'HEAD', 'PUT', 'PATCH', 'DELETE' ) ) ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
        }
        
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        
        if ( preg_match( '/^https:/is', trim( $this->uri ) ) ) {
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
        }
        
        // debugging
        curl_setopt( $ch, CURLOPT_VERBOSE, true );
        $fh = fopen( 'php://temp', 'w+' );
        curl_setopt( $ch, CURLOPT_STDERR, $fh );
        // -debugging
        
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $this->timeout );
        
        if ( ! is_null( $this->port ) ) {
            curl_setopt( $ch, CURLOPT_PORT, $this->port );
        }
        
        if ( $this->proxy_host && $this->proxy_port ) {
            curl_setopt( $ch, CURLOPT_PROXY, $this->proxy_host . ':' . $this->proxy_port );
            if ( $this->proxy_type ) {
                curl_setopt( $ch, CURLOPT_PROXYTYPE, $this->proxy_type );
            }
            
            if ( $this->proxy_user ) {
                curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $this->proxy_user . ':' . $this->proxy_pass );
            }
        }
        
        if ( $this->cookie_file ) {
            curl_setopt( $ch, CURLOPT_COOKIEFILE, $this->cookie_file );
            curl_setopt( $ch, CURLOPT_COOKIEJAR, $this->cookie_file );
        }
        
        if ( ! is_null( $data ) && in_array( $method, array( 'PUT', 'PATCH', 'DELETE' ) ) ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $data );
        }
        
        if ( ! is_null( $this->encoding ) ) {
            curl_setopt( $ch, CURLOPT_ENCODING, $this->encoding );
        }
        
        if ( ! is_null( $this->referer ) ) {
            curl_setopt( $ch, CURLOPT_REFERER, $this->referer );
        }
        
        if ( ! is_null( $data ) ) {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        }
        
        if ( $this->user_agent ) {
            curl_setopt( $ch, CURLOPT_USERAGENT, $this->user_agent );
        }
        
        $headers = array();
        foreach ( $this->headers as $name => $value ) {
            $headers[] = $name . ': ' . $value;
        }
        
        if ( count( $headers ) ) {
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        }
        
        $result = curl_exec( $ch );
        
        $this->request_info = curl_getinfo( $ch );
        //print_r( $this->request_info );
        
        rewind( $fh );
        $this->debug_info = stream_get_contents( $fh );
        
        curl_close( $ch );
        
        $this->client = null;
        
        return $result;
    }

}


