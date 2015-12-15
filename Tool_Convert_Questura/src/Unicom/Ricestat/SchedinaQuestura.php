<?php

namespace Unicom\Ricestat;

class SchedinaQuestura implements \Iterator {
    
    /**
     *
     * @var string 
     */
    protected $file;
    
    /**
     *
     * @var resource 
     */
    protected $fp;
    
    /**
     *
     * @var integer 
     */
    protected $position = 0;
    
    /**
     *
     * @var string 
     */
    protected $current_line;
    
    /**
     *
     * @var string 
     */
    protected $parsing_rules = 'Tipo Alloggiato;2|Data Arrivo;10|NumeroGiorni di Permanenza;2|Cognome;50|Nome;30|Sesso;1|Data Nascita;10|Comune Nascita;9|Provincia Nascita;2|Stato Nascita;9|Cittadinanza;9|Tipo Documento;5|Numero Documento;20|Luogo Rilascio Documento;9';
    
    /**
     *
     * @var boolean 
     */
    protected $valid = true;
    
    /**
     * 
     * @param string $file
     */
    public function __construct( $file ) {
        if ( ! is_readable( $file ) ) {
            throw new \InvalidArgumentException( "File $file cannot be read!" );
        }
        
        $this->file = $file;
        
        $this->fp   = fopen( $this->file, 'r' );
    }
    
    /**
     * 
     * @return array
     */
    public function current() {
        return $this->parse( $this->current_line );
    }
    
    /**
     * 
     * @param string $line
     * @return array
     */
    protected function parse( $line ) {
        $item = array (
                'Tipo Alloggiato' => '',
                'Data Arrivo' => '',
                'NumeroGiorni di Permanenza' => '',
                'Cognome' => '',
                'Nome' => '',
                'Sesso' => '',
                'Data Nascita' => '',
                'Comune Nascita' => '',
                'Provincia Nascita' => '',
                'Stato Nascita' => '',
                'Cittadinanza' => '',
                'Tipo Documento' => '',
                'Numero Documento' => '',
                'Luogo Rilascio Documento' => '',
                'Data Partenza' => '',
            );
        
        if ( '' == preg_replace( '/[\s\r\n]/', '', $line ) ) {
            return $item;
        }
        
        $rules = explode( '|', $this->parsing_rules );
        $i = 0;
        
        foreach ( $rules as $rule ) {
            $p = explode( ';', $rule );
            if ( 2 == count( $p ) ) {
                $name   = $p[0];
                $length = (int) $p[1];
                
                $item[ $name ] = trim( substr( $line, $i, $length ) );
                
                $func = 'parse' . preg_replace( '/[^a-z]/is', '', $name );
                if ( method_exists( $this, $func ) ) {
                    $item[ $name ] = $this->$func( $item[ $name ] );
                }
                
                $i    += $length;
            }
        }
        
        return $this->extraParsing( $item );
    }
    
    protected function extraParsing( $item ) {
        $item['Data Partenza'] = date( 'Y-m-d', strtotime( $item['Data Arrivo'] ) + $item['NumeroGiorni di Permanenza'] * 24 * 60 * 60 );
        return $item;
    }
    
    /**
     * 
     * @param string $date
     * @return string
     */
    protected function parseDate( $date ) {
        if ( preg_match( '/(\d{2})\/(\d{2})\/(\d{4})/is', $date ) ) {
            $date = preg_replace( '/(\d{2})\/(\d{2})\/(\d{4})/is', '$3-$2-$1', $date );
        }
        return $date;
    }
    
    /**
     * 
     * @param string $data
     * @return string
     */
    protected function parseDataArrivo( $data ) {
        return $this->parseDate( $data );
    }
    
    /**
     * 
     * @param string $data
     * @return string
     */
    protected function parseDataNascita( $data ) {
        return $this->parseDate( $data );
    }

    /**
     * 
     * @return integer
     */
    public function key() {
        return $this->position;
    }

    /**
     * 
     */
    public function next() {
        $this->valid        = ! feof( $this->fp );
        if ( $this->valid ) {
            $this->current_line = fgets( $this->fp, 4096 );
            $this->position     = ftell( $this->fp );
        }
    }

    /**
     * 
     */
    public function rewind() {
        rewind( $this->fp );
        $this->valid        = ! feof( $this->fp );
        if ( $this->valid ) {
            $this->current_line = fgets( $this->fp, 4096 );
            $this->position     = ftell( $this->fp );
        }
    }

    /**
     * 
     * @return boolean
     */
    public function valid() {
        return $this->valid;
    }

}


