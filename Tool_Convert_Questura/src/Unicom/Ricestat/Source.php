<?php

namespace Unicom\Ricestat;

/**
 * 
 */
class Source {

    /**
     *
     * @var string 
     */
    protected $type;
    
    /**
     *
     * @var string 
     */
    protected $id;
    
    /**
     *
     * @var string 
     */
    protected $message_password;
    
    /**
     * 
     * @param string $type
     * @param string $id
     * @param string $message_password
     */
    public function __construct( $type, $id, $message_password ) {
        $this->type = $type;
        $this->id = $id;
        $this->message_password = $message_password;
    }
    
    /**
     * 
     * @return string
     */
    public function getType( ) {
        return $this->type;
    }
    
    /**
     * 
     * @return string
     */
    public function getId( ) {
        return $this->id;
    }
    
    /**
     * 
     * @return string
     */
    public function getMessagePassword( ) {
        return $this->message_password;
    }
    
    public function asXML() {
        return '<Source><RequestorID Type="' . $this->type . '" ID="' . $this->id . '" MessagePassword="' . $this->message_password . '" /></Source>';
    }
    
}
