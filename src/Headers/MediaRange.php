<?php

namespace Http;



class MediaRange {

    private $type;

    private $subtype;


    public function __construct($mimeType) {

        $parts = array_map(function($part) { return trim($part); }, explode("/", $mimeType));

        list($this->type,$this->subtype) = $parts;
    }


    public function getType() {

        return $this->type;
    }

    
    public function getSubtype() {

        return $this->subtype;
    }


    public function includes(MediaRange $range) {

        $type = $range->getType();
        $subtype = $range->getSubtype();

        if($this->subtype == "*" && $type == $this->type) {
            return true;
        } else if($this->type == $type && $this->subtype == $subtype) {
            return true;
        }

        return false;
    }


}