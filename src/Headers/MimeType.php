<?php

namespace Http;



class MimeType {

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
}