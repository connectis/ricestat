<?php

date_default_timezone_set('Africa/Abidjan');

class Unicom_Autoload {
    
    protected static $paths = array();
    
    protected static $namespace_paths = array();
    
    public static function register( ) {
        spl_autoload_register( array( __CLASS__, 'load' ) );
    }
    
    public static function load( $class ) {
        $file_path = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';
        if ( strpos( $class, '\\' ) ) {
            $namespace = ltrim( substr( $class, 0, strrpos( $class, '\\' ) ), '\\' );
            if ( array_key_exists( $namespace, self::$namespace_paths ) && is_readable( self::$namespace_paths[ $namespace ] . '/' . $file_path ) ) {
                require_once self::$namespace_paths[ $namespace ] . '/' . $file_path;
                return;
            }
        } 
        
        foreach ( self::$paths as $path ) {
            if ( is_readable( $path . '/' . $file_path ) ) {
                require_once $path . '/' . $file_path;
                break;
            }
        }
        
        //echo "CLASS=" . $file_path . "\n";
    }
    
    public static function registerPath( $path ) {
        if ( is_dir( $path ) && ! in_array( $path, self::$paths ) ) {
            self::$paths[] = $path;
        }
    }
    
    public static function registerNamespacePath( $namespace, $path ) {
        $namespace = ltrim( $namespace, '\\' );
        if ( ! in_array( $namespace, self::$namespace_paths ) ) {
            self::$namespace_paths[ $namespace ] = $path;
        }
    }
    
}
