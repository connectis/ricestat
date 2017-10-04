<?php

namespace Unicom\Ricestat\SearchCriterion;

class DateRange extends SearchCriterion {
    
    /**
     *
     * @var string 
     */
    protected $start_date;
    
    /**
     *
     * @var string 
     */
    protected $end_date;
    
    public function __construct( $start_date, $end_date ) {
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $start_date ) ) ) {
            throw New \InvalidArgumentException( "Invalid start date in range: $start_date" );
        }
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $end_date ) ) ) {
            throw New \InvalidArgumentException( "Invalid end date in range: $end_date" );
        }
        $this->start_date = trim( $start_date );
        $this->end_date = trim( $end_date );
    }
    
    public function getStart() {
        return $this->start_date;
    }
    
    public function getEnd() {
        return $this->end_date;
    }
    
    public function asXML( ) {
        return '<DateRange Start="' . $this->start_date . '" End="' . $this->end_date . '" />';
    }
    
}


