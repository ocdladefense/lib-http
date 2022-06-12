<?php

namespace Http;



class MediaRange {

    private $type;

    private $subtype;


    public function __construct($mimeType) {

        $parts = array_map(function($part) { return trim($part); }, explode("/", $mimeType));

        if(count($parts) !== 2) {
            throw new \Exception("INVALID_HEADER_VALUE_ERROR: The specified media range syntax is invalid: {$mimeType}.");
        }

        $subtype = explode("+",$parts[1]);
        $this->subtype = array_pop($subtype);

        $this->type = $parts[0];
    }


    public function getType() {

        return $this->type;
    }

    
    public function getSubtype() {

        return $this->subtype;
    }


    public function includes(MimeType $mime) {

        $type = $mime->getType();
        $subtype = $mime->getSubtype();


        if($this->subtype == "*" && $type == $this->type) {
            return true;
        } else if($this->type == $type && $this->subtype == $subtype) {
            return true;
        } else if($this->type == "*") {
            return true;
        }

        return false;
    }


}