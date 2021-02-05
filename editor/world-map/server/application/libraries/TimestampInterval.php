<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'Timestamp.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimestampInterval
 *
 * @author Ivan Miranda
 */
class TimestampInterval {
    public $timestamp;

    public function __construct($timestamp = 0) {
        if(is_numeric($timestamp))
            $this->timestamp = $timestamp;
        else{
            $tempo = explode(":",$timestamp);
            $hora = $tempo[0];
            $min = $tempo[1];
            $sec = $tempo[2];

            $this->timestamp = $hora * 3600 + $min * 60 + $sec;
        }
    }

    public function __toString(){
        $h = $this->timestamp / 3600;
        $ho = $this->timestamp > 0 ? floor($h): ceil($h);

        $m = ($this->timestamp - ($ho*3600)) / 60;
        $mins = $this->_zero_fill(abs($this->timestamp > 0 ? floor($m): ceil($m)));

        $secs = $this->_zero_fill(abs($this->timestamp % 60));

        $hours = $this->_zero_fill($ho);

        return "$hours:$mins:$secs";
    }

    private function _zero_fill($num){
        return str_pad($num, 2, '0', STR_PAD_LEFT);
    }
}

/* End of file TimestampInterval.php */
/* Location:  */
