<?php

namespace Unicom\Http\Transport;

abstract class Transport {
    
    /**
     *
     * @var string 
     */
    protected $uri;
    
    /**
     *
     * @var boolean 
     */
    protected $autoreferer = null;
    
    /**
     *
     * @var boolean 
     */
    protected $cookie_session = null;
    
    /**
     *
     * @var boolean 
     */
    protected $auto_redirect = null;
    
    /**
     *
     * @var boolean 
     */
    protected $retrieve_headers = null;
    
    /**
     *
     * @var boolean 
     */
    protected $enable_proxy_tunnel = null;
    
    /**
     *
     * @var integer 
     */
    protected $connect_timeout = 15;
    
    /**
     *
     * @var integer 
     */
    protected $timeout = 60;
    
    /**
     *
     * @var integer 
     */
    protected $port = null;
    
    /**
     *
     * @var string 
     */
    protected $proxy_host;
    
    /**
     *
     * @var int 
     */
    protected $proxy_port;
    
    /**
     *
     * @var string 
     */
    protected $proxy_user;
    
    /**
     *
     * @var string 
     */
    protected $proxy_pass;
    
    /**
     *
     * @var string 
     */
    protected $proxy_type;
    
    /**
     *
     * @var string 
     */
    protected $cookie_file;
    
    /**
     *
     * @var string 
     */
    protected $encoding = null;
    
    /**
     *
     * @var string 
     */
    protected $referer = null;
    
    /**
     *
     * @var string 
     */
    protected $user_agent = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0';
    
    /**
     *
     * @var string 
     */
    protected $auth_user;
    
    /**
     *
     * @var string 
     */
    protected $auth_pass;
    
    /**
     *
     * @var mixed 
     */
    protected $client;
    
    /**
     *
     * @var string 
     */
    protected $debug_info;
    
    /**
     *
     * @var array 
     */
    protected $request_info = array();
    
    /**
     *
     * @var array 
     */
    protected $headers = array();
    
    //const PROXY_SOCKS4 = CURLPROXY_SOCKS4;
    //const PROXY_SOCKS5 = CURLPROXY_SOCKS5;
    //const PROXY_SOCKS4A = CURLPROXY_SOCKS4A;
    //const PROXY_SOCKS5_HOSTNAME = CURLPROXY_SOCKS5_HOSTNAME;
    
    /**
     * 
     * @param string $uri
     */
    public function __construct( $uri ) {
        $this->uri = $uri;
    }
    
    public function setUri( $uri ) {
        $this->uri = $uri;
    }
    
    abstract function request( $method, $data = null );
    
    public function get( ) {
        return $this->request( 'get' );
    }
    
    public function head( ) {
        return $this->request( 'head' );
    }
    
    public function post( $data ) {
        return $this->request( 'post', $data );
    }
    
    public function put( $data ) {
        return $this->request( 'put', $data );
    }
    
    public function patch( $data ) {
        return $this->request( 'patch', $data );
    }
    
    public function delete( ) {
        return $this->request( 'delete' );
    }
    
    /**
     * 
     * @param boolean $enabled
     * @return \Unicom\Http\Transport\Transport
     */
    public function setAutoReferer( $enabled ) {
        $this->autoreferer = (bool) $enabled;
        return $this;
    }
    
    /**
     * 
     * @param boolean $enabled
     * @return \Unicom\Http\Transport\Transport
     */
    public function setIsCookieSession( $enabled ) {
        $this->cookie_session = (bool) $enabled;
        return $this;
    }
    
    /**
     * 
     * @param boolean $enabled
     * @return \Unicom\Http\Transport\Transport
     */
    public function setRetriveHeaders( $enabled ) {
        $this->retrieve_headers = (bool) $enabled;
        return $this;
    }
    
    /**
     * 
     * @param boolean $enabled
     * @return \Unicom\Http\Transport\Transport
     */
    public function proxyTunnelEnabled( $enabled ) {
        $this->enable_proxy_tunnel = (bool) $enabled;
        return $this;
    }
    
    /**
     * 
     * @param integer $timeout
     * @return \Unicom\Http\Transport\Transport
     */
    public function setConnectTimeout( $timeout ) {
        $this->connect_timeout = (int) $timeout;
        return $this;
    }
    
    /**
     * 
     * @param integer $timeout
     * @return \Unicom\Http\Transport\Transport
     */
    public function setTimeout( $timeout ) {
        $this->timeout = (int) $timeout;
        return $this;
    }
    
    /**
     * 
     * @param integer $port
     * @return \Unicom\Http\Transport\Transport
     */
    public function setPort( $port ) {
        $this->port = (int) $port;
        return $this;
    }
    
    /**
     * 
     * @param string $host
     * @param integer $port
     * @param string|null $username
     * @param integer|null $password
     * @param string|null $type
     * @return \Unicom\Http\Transport\Transport
     */
    public function setProxy( $host, $port, $username= null, $password = null, $type = null ) {
        $this->proxy_host = $host;
        $this->proxy_port = (int) $port;
        
        if ( $username ) {
            $this->proxy_user = $username;
        }
        if ( $password ) {
            $this->proxy_pass = $password;
        }
        if ( $type ) {
            $this->proxy_type = $type;
        }
        return $this;
    }
    
    /**
     * 
     * @param string $filepath
     * @return \Unicom\Http\Transport\Transport
     */
    public function setCookieFile( $filepath ) {
        $this->cookie_file = $filepath;
        return $this;
    }
    
    /**
     * 
     * @param string $encoding
     */
    public function setEncoding( $encoding ) {
        $this->encoding = $encoding;
        return $this;
    }
    
    /**
     * 
     * @param string $referer
     * @return \Unicom\Http\Transport\Transport
     */
    public function setReferer( $referer ) {
        $this->referer = $referer;
        return $this;
    }
    
    /**
     * 
     * @param string $user_agent
     * @return \Unicom\Http\Transport\Transport
     */
    public function setUserAgent( $user_agent ) {
        $this->user_agent = $user_agent;
        return $this;
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return \Unicom\Http\Transport\Transport
     */
    public function setAuthUserPass( $username, $password ) {
        $this->auth_user = $username;
        $this->auth_pass = $password;
        return $this;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getClient() {
        return $this->client;
    }
    
    /**
     * 
     * @return string
     */
    public function getDebugInfo( ) {
        return $this->debug_info;
    }
    
    /**
     * 
     * @return null|array
     */
    public function getLastRequestInfo( ) {
        return $this->request_info;
    }
    
    /**
     * 
     * @return \Unicom\Http\Transport\Transport
     */
    public function resetReferer( ) {
        $this->referer = null;
        return $this;
    }
    
    /**
     * 
     * @return \Unicom\Http\Transport\Transport
     */
    public function resetProxy( ) {
        $this->proxy_host = null;
        $this->proxy_port = null;
        $this->proxy_type = null;
        $this->proxy_user = null;
        $this->proxy_pass = null;
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @return \Unicom\Http\Transport\Transport
     */
    public function setHeader( $name, $value ) {
        $this->headers[ $name ] = $value;
        return $this;
    }
    
    /**
     * 
     * @return \Unicom\Http\Transport\Transport
     */
    public function resetHeaders( ) {
        $this->headers = array();
        return $this;
    }
}


