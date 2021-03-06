<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'TimestampInterval.php';

/**
 * Description of Timestamp
 *
 * @author Ivan Miranda
 */
class Timestamp {
    public $timestamp;

    public function __construct($timestamp = "now") {
        if(is_numeric($timestamp))
            $this->timestamp = $timestamp;
        else{
            if(strtolower($timestamp) === "now"){
                $this->timestamp = time();
            }
            else{
                $this->timestamp = strtotime($timestamp);
            }
        }
    }

    /**
     *
     * @return String
     */
    public function __toString(){
        return date("Y-m-d H:i:s", $this->timestamp);
    }

    /**
     *
     * @param TimestampInterval $interval
     */
    public function add($interval){
        return new Timestamp($this->timestamp + $interval->timestamp);
    }

    /**
     *
     * @param TimestampInterval $interval
     */
    public function sub($interval){
        return new Timestamp($this->timestamp - $interval->timestamp);
    }

    /**
     *
     * @param Timestamp $timestamp
     *
     * @return TimestampInterval this - timestamp
     */
    public function diff($timestamp){
        return new TimestampInterval($this->timestamp - $timestamp->timestamp);
    }

    /**
     *
     * @param Timestamp $timestamp
     * @return bool
     */
    public function greater($timestamp){
        return $this->timestamp > $timestamp->timestamp;
    }
    /**
     *
     * @param Timestamp $timestamp
     * @return bool
     */
    public function lowest($timestamp){
        return $this->timestamp < $timestamp->timestamp;
    }

    /**
     * TimestampInterval decorrido desde tempo
     *
     * @return TimestampInterval
     */
    public function remainder($tempo){
        return $tempo->diff($this);
    }
}

/* End of file Timestamp.php */
/* Location:  */
