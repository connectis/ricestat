<?php

namespace Unicom\Ricestat;

use Unicom\Ricestat\Service\REST;

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
        
        if ( ! $this->validate_format( $file ) ) {
            throw new \InvalidArgumentException( "The selected format does not match your file. Please check your file or chosen format before proceeding" );
        }
        
        $this->file = $file;
        
        $this->fp   = fopen( $this->file, 'r' );
    }
    
    /**
     * 
     * @param string $file
     * @return boolean
     */
    public function validate_format( $file ) {
        $result = false;
        if ( preg_match_all( '/;(\d+)/is', $this->parsing_rules, $m ) ) {
            $expected_line_length = array_sum( $m[1] );
            if ( $fp = fopen( $file, 'r' ) ) {
                $line = fgets( $fp, 4096 );
                $diff = strlen( $line )-$expected_line_length;
                $result = ( $diff >= 0 && $diff <= 10 );
                fclose( $fp );
            }
        }
        return $result;
    }
    
    /**
     * 
     * @return array
     */
    public function current() {
        return $this->parse( );
    }
    
    /**
     * 
     * @return string
     */
    protected function getParsingRules( ) {
        return $this->parsing_rules;
    }
    
    /**
     * 
     * @return array
     */
    protected function parse( ) {
        $item = $this->getEmptyItem( );
        
        if ( '' == preg_replace( '/[\s\r\n]/', '', $this->current_line ) ) {
            return $item;
        }
        
        $rules = explode( '|', $this->getParsingRules( ) );
        $i = 0;
        
        foreach ( $rules as $rule ) {
            $p = explode( ';', $rule );
            if ( 2 == count( $p ) ) {
                $name   = $p[0];
                $length = (int) $p[1];
                
                $item[ $name ] = trim( substr( $this->current_line, $i, $length ) );
                
                $func = 'parse' . preg_replace( '/[^a-z]/is', '', $name );
                if ( method_exists( $this, $func ) ) {
                    $item[ $name ] = $this->$func( $item[ $name ] );
                }
                
                $i    += $length;
            }
        }
        
        return $this->extraParsing( $item );
    }
    
    protected function getEmptyItem( ) {
        static $item = null;
        if ( is_null( $item ) ) {
            $headers = $this->getHeaders( );
            $item    = array_map( function( $v ) { return ''; }, array_flip( $headers ) );
        }
        return $item;
    }
    
    /**
     * 
     * @return array
     */
    protected function getHeaders( ) {
        $headers = array();
        if ( preg_match_all( '/\|?(.*?);\d+/is', $this->getParsingRules( ), $m ) ) {
            $headers = array_map( 'trim', $m[1] );
        }
        return array_filter( array_merge( $headers, $this->getExtraHeaders() ) );
    }
    
    protected function getExtraHeaders( ) {
        return array( 'Data Partenza' );
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
        $date = preg_replace( '/[^\d\/-]/', '', $date );
        if ( preg_match( '/(\d{2})\/(\d{2})\/(\d{4})/is', $date ) ) {
            $date = preg_replace( '/(\d{2})\/(\d{2})\/(\d{4})/is', '$3-$2-$1', $date );
        }
        if ( ! preg_match( '/\d+/is', $date ) ) {
            $date = '';
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
    
    /**
     * 
     * @param string $file_format
     * @return string
     */
    public static function get_processor( $file_format ) {
        $class = '';
        if ( ! $file_format ) {
            return __CLASS__;
        }
        
        switch ( $file_format ) {
            case 'sardinia_emilia':
                $class = 'Sired';
                break;
        }
        
        if ( ! class_exists( $class ) && class_exists( 'Unicom\Ricestat\\' . $class ) ) {
            $class = 'Unicom\Ricestat\\' . $class;
        }
        
        if ( ! class_exists( $class ) OR ( $class !== __CLASS__ AND ! is_subclass_of( $class, __CLASS__ ) ) ) {
            $class = '';
        }

        return $class;
    }
    
    public static function get_groups( $file ) {
        $sq = new self( $file );
        
        // organize items in groups ( 2 per Ospite group: TipoAlloggiato(17, 18) and TipoAlloggiato(19, 20) or a OspiteSingolo with a single Ospite insid )
        $groups   = array();
        $IdGruppo = null;
        $group    = new Gruppo( $IdGruppo );
        foreach ( $sq as $item ) {
            if ( empty( $item['Tipo Alloggiato'] ) ) {
                continue;
            }

            if ( ! $IdGruppo ) {
                $IdGruppo = rand( 100000, 999999 );
            }

            // print_r( $item );

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
                $Id         = rand( 100000, 999999 );
                $item['Id'] = $Id;
                $IdCamera   = rand( 100000, 999999 ); 
                $ospite     = new Ospite( $Id, $IdCamera, $item['Tipo Alloggiato'] ); // Id, IdCamera, TipoAlloggiato
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
        
        return $groups;
    }
    
    protected static function get_operation_data( SchedineRQ $rq, $file ) {
        $groups = SchedinaQuestura::get_groups( $file );
        $feeds[] = array( 'operation' => 'inserimentoAlloggiati', 'data' => $rq->inserimentoAlloggiati( $groups ) );
        return $feeds;
    }
    
    public static function process( $auth, $file, $log_dir = null ,$endpoint) {
        if ( count( $auth ) !== 2 ) {
            throw new \Exception( "Missing authentication information" );
        }
        
        $results = array( );
        
        // create rest client 
        $rest_cli = new REST\Client($endpoint);
    
        // purge old logs 
        if ( $log_dir ) {
            if ( ! is_dir( dirname( $log_dir ) ) && is_dir( dirname( dirname( $log_dir ) ) ) ) {
                mkdir( dirname( $log_dir ), 0755, true );
            }
            
            self::purge_log_files( $log_dir );
        }
        
        $rq     = new SchedineRQ( Source::from_array( $auth[0] ), Source::from_array( $auth[1] ) );
        $items  = call_user_func_array( array( get_called_class(), 'get_operation_data' ), array( $rq, $file ) );
        
        //print_r( $items );
        //die();

        foreach ( $items as $item ) {
            if ( empty( $item['operation'] ) OR empty( $item['data'] ) ) {
                continue;
            }
            
            //die( $item['data'] );
            
            $log_file = $log_dir . 'log-' . $item['operation'] . '-' . date( 'Y-m-d_His' ) . '.txt';
            $rest_cli->setLogFile( $log_file );
            $resp = $rest_cli->send( $item['data'] );
            //print_r( $resp );
            //die();
            
            if ( $resp->getError() ) {
                $error = $resp->getError();
                if ( isset( $error['Type'] ) ) {
                    $error = $error['Type'] . ( isset( $error['Code'] ) ? '. Error code: ' . $error['Code'] : '' ) . ( isset( $error['ShortText'] ) ? '. ' . $error['ShortText'] : '' );
                }
                $results[] = array( 'type' => 'error', 'msg' => sprintf( "Failed operation: %s - reason: %s - [log_file=%s]", $item['operation'], $error, $log_file ) );
            } else {
                $results[] = array( 'type' => 'success', 'msg' => sprintf( "Successful operation: %s - [log_file=%s]", $item['operation'], $log_file ) );
            }
        }
        
        //print_r( $results );
        //die();

        return $results;
    }
    
    /**
     * 
     * @return int
     */
    protected static function purge_log_files( $dir ) {
        if ( ! is_dir( $dir ) ) {
            return;
        }
        
        $cnt = 0;
        $files = glob( $dir . '/*' );
        foreach ( $files as $file ) {
            if ( preg_match( '/\-\d{4}\-\d{2}\-\d{2}\.txt$/is', trim( $file ) ) ) {
                $mtime = filemtime( $file );
                if ( $mtime < time()-1*60*60 ) {
                    unlink( $file );
                    $cnt++;
                }
            }
        }
        return $cnt;
    }


}


